namespace StudentManager.Models;

public class Student
{
    public int Id { get; set; }
    public string NIM { get; set; } = "";
    public string Nama { get; set; } = "";
    public string Jurusan { get; set; } = "";
    public string Gender { get; set; } = "";
    public string Email { get; set; } = "";

    // Salinan data untuk form. Form mengedit salinan ini, bukan objek di DataGrid,
    // sehingga perubahan baru "resmi" setelah tombol Simpan ditekan.
    public Student Clone() => new()
    {
        Id = Id,
        NIM = NIM,
        Nama = Nama,
        Jurusan = Jurusan,
        Gender = Gender,
        Email = Email
    };
}
