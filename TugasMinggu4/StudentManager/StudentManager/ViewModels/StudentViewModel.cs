using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Input;
using StudentManager.Data;
using StudentManager.Models;

namespace StudentManager.ViewModels;

public class StudentViewModel : INotifyPropertyChanged
{
    private readonly StudentRepository _repository = new();

    // Seluruh data di database (dipakai untuk statistik agar tidak ikut berubah saat Search).
    private List<Student> _allStudents = new();

    // ===== Data untuk DataGrid & ComboBox =====
    public ObservableCollection<Student> Students { get; } = new();
    public IReadOnlyList<string> JurusanOptions { get; } = new[] { "Informatika", "Sistem Informasi" };
    public IReadOnlyList<string> GenderOptions { get; } = new[] { "Laki-laki", "Perempuan" };

    // ===== Baris yang dipilih di DataGrid =====
    private Student? _selectedStudent;
    public Student? SelectedStudent
    {
        get => _selectedStudent;
        set
        {
            _selectedStudent = value;
            OnPropertyChanged();

            // Pilih baris -> isi form dengan salinan datanya (mode Edit).
            if (value != null)
                Form = value.Clone();
        }
    }

    // ===== Data yang sedang diisi di form =====
    private Student _form = new();
    public Student Form
    {
        get => _form;
        set
        {
            _form = value;
            OnPropertyChanged();
            OnPropertyChanged(nameof(FormTitle));
        }
    }

    public string FormTitle => Form.Id == 0 ? "INPUT MAHASISWA" : "EDIT MAHASISWA";

    // ===== Pencarian =====
    private string _searchText = "";
    public string SearchText
    {
        get => _searchText;
        set
        {
            _searchText = value;
            OnPropertyChanged();
        }
    }

    // ===== Pesan status (validasi / error) =====
    private string _statusMessage = "";
    public string StatusMessage
    {
        get => _statusMessage;
        set
        {
            _statusMessage = value;
            OnPropertyChanged();
        }
    }

    // ===== Statistik (dihitung dari seluruh data, bukan hasil filter) =====
    public int TotalStudents => _allStudents.Count;
    public int TotalInformatika => _allStudents.Count(x => x.Jurusan == "Informatika");
    public int TotalSistemInformasi => _allStudents.Count(x => x.Jurusan == "Sistem Informasi");
    public int TotalLakiLaki => _allStudents.Count(x => x.Gender == "Laki-laki");
    public int TotalPerempuan => _allStudents.Count(x => x.Gender == "Perempuan");

    // ===== Commands =====
    public ICommand SaveCommand { get; }
    public ICommand ResetCommand { get; }
    public ICommand SearchCommand { get; }
    public ICommand EditCommand { get; }
    public ICommand DeleteCommand { get; }

    public StudentViewModel()
    {
        SaveCommand = new RelayCommand(Save);
        ResetCommand = new RelayCommand(Reset);
        SearchCommand = new RelayCommand(Search);
        EditCommand = new RelayCommand(Edit);
        DeleteCommand = new RelayCommand(Delete);

        LoadData();
    }

    private void LoadData()
    {
        try
        {
            _allStudents = _repository.GetAll();
            Search();
        }
        catch (Exception ex)
        {
            StatusMessage = "Gagal memuat data: " + ex.Message;
        }
    }

    private void Save()
    {
        string? error = Validate(Form);
        if (error != null)
        {
            StatusMessage = error;
            return;
        }

        try
        {
            bool isNew = Form.Id == 0;
            if (isNew)
                _repository.Insert(Form);
            else
                _repository.Update(Form);

            LoadData();
            Reset();
            StatusMessage = isNew ? "Data berhasil ditambahkan." : "Data berhasil diperbarui.";
        }
        catch (Exception ex)
        {
            StatusMessage = "Gagal menyimpan: " + ex.Message;
        }
    }

    private void Edit(object? parameter)
    {
        if (parameter is Student student)
        {
            SelectedStudent = student;
            StatusMessage = "";
        }
    }

    private void Delete(object? parameter)
    {
        // Tombol Delete di baris mengirim Student lewat CommandParameter.
        var student = parameter as Student ?? SelectedStudent;
        if (student == null || student.Id == 0)
            return;

        var answer = MessageBox.Show(
            $"Hapus data {student.Nama} ({student.NIM})?",
            "Konfirmasi Hapus",
            MessageBoxButton.YesNo,
            MessageBoxImage.Question);

        if (answer != MessageBoxResult.Yes)
            return;

        try
        {
            _repository.Delete(student.Id);
            LoadData();
            Reset();
            StatusMessage = "Data berhasil dihapus.";
        }
        catch (Exception ex)
        {
            StatusMessage = "Gagal menghapus: " + ex.Message;
        }
    }

    private void Search()
    {
        try
        {
            var result = string.IsNullOrWhiteSpace(SearchText)
                ? _allStudents
                : _repository.Search(SearchText.Trim());

            Students.Clear();
            foreach (var student in result)
                Students.Add(student);

            RefreshStatistics();
        }
        catch (Exception ex)
        {
            StatusMessage = "Gagal mencari data: " + ex.Message;
        }
    }

    private void Reset()
    {
        SelectedStudent = null;   // lepas pilihan di DataGrid
        Form = new Student();     // form kembali kosong (mode Input)
        StatusMessage = "";
    }

    private static string? Validate(Student s)
    {
        if (string.IsNullOrWhiteSpace(s.NIM)) return "NIM wajib diisi.";
        if (string.IsNullOrWhiteSpace(s.Nama)) return "Nama wajib diisi.";
        if (string.IsNullOrWhiteSpace(s.Jurusan)) return "Jurusan wajib dipilih.";
        if (string.IsNullOrWhiteSpace(s.Gender)) return "Gender wajib dipilih.";
        if (!string.IsNullOrWhiteSpace(s.Email) && !s.Email.Contains('@')) return "Format email tidak valid.";
        return null;
    }

    private void RefreshStatistics()
    {
        OnPropertyChanged(nameof(TotalStudents));
        OnPropertyChanged(nameof(TotalInformatika));
        OnPropertyChanged(nameof(TotalSistemInformasi));
        OnPropertyChanged(nameof(TotalLakiLaki));
        OnPropertyChanged(nameof(TotalPerempuan));
    }

    public event PropertyChangedEventHandler? PropertyChanged;

    protected void OnPropertyChanged([CallerMemberName] string? propertyName = null)
    {
        PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
    }
}
