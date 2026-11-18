using System.Windows;
using StudentRegistration.Repositories;

namespace StudentRegistration;

public partial class MainWindow : Window
{
    private readonly StudentRepository _studentRepository;

    public MainWindow()
    {
        InitializeComponent();

        _studentRepository = new StudentRepository();
        LoadPrograms();
        LoadStudents();
    }

    private void LoadStudents()
    {
        try
        {
            var students = _studentRepository.GetAll();
            StudentDataGrid.ItemsSource = students;

            TotalTextBlock.Text =
                $"Total Mahasiswa: {_studentRepository.GetTotalStudents()}";
        }
        catch (Exception ex)
        {
            MessageBox.Show(
                ex.Message,
                "Database Error",
                MessageBoxButton.OK,
                MessageBoxImage.Error);
        }
    }

    private void LoadPrograms()
    {
        try
        {
            ProgramComboBox.ItemsSource = _studentRepository.GetPrograms();
        }
        catch (Exception ex)
        {
            MessageBox.Show(ex.Message, "Database Error", MessageBoxButton.OK, MessageBoxImage.Error);
        }
    }

    private void AddButton_Click(object sender, RoutedEventArgs e)
    {
        if (string.IsNullOrWhiteSpace(NimTextBox.Text))
        {
            MessageBox.Show("NIM wajib diisi.", "Validasi", MessageBoxButton.OK, MessageBoxImage.Warning);
            NimTextBox.Focus();
            return;
        }

        if (string.IsNullOrWhiteSpace(NameTextBox.Text))
        {
            MessageBox.Show("Nama wajib diisi.", "Validasi", MessageBoxButton.OK, MessageBoxImage.Warning);
            NameTextBox.Focus();
            return;
        }

        if (ProgramComboBox.SelectedValue is not int programId)
        {
            MessageBox.Show("Program studi wajib dipilih.", "Validasi", MessageBoxButton.OK, MessageBoxImage.Warning);
            ProgramComboBox.Focus();
            return;
        }

        try
        {
            _studentRepository.AddStudent(new Models.Student
            {
                NIM = NimTextBox.Text.Trim(),
                Name = NameTextBox.Text.Trim(),
                ProgramId = programId,
                BirthDate = BirthDatePicker.SelectedDate,
                Address = AddressTextBox.Text.Trim(),
                PhoneNumber = PhoneTextBox.Text.Trim()
            });

            NimTextBox.Clear();
            NameTextBox.Clear();
            ProgramComboBox.SelectedItem = null;
            BirthDatePicker.SelectedDate = null;
            AddressTextBox.Clear();
            PhoneTextBox.Clear();

            LoadStudents();
            MessageBox.Show("Data siswa berhasil ditambahkan.", "Sukses", MessageBoxButton.OK, MessageBoxImage.Information);
        }
        catch (Exception ex)
        {
            MessageBox.Show(ex.Message, "Database Error", MessageBoxButton.OK, MessageBoxImage.Error);
        }
    }

    private void RefreshButton_Click(object sender, RoutedEventArgs e)
    {
        LoadStudents();
    }
}
