using System.Data;
using Microsoft.Data.SqlClient;
using StudentManager.Models;

namespace StudentManager.Data;

public class StudentRepository
{
    private const string SelectColumns = "SELECT Id, NIM, Nama, Jurusan, Gender, Email FROM Students ";

    private readonly string connectionString = DbConfig.StudentDbConnectionString;

    // READ
    public List<Student> GetAll()
    {
        using var connection = new SqlConnection(connectionString);
        using var command = new SqlCommand(SelectColumns + "ORDER BY Id DESC", connection);

        connection.Open();
        return ReadStudents(command);
    }

    // SEARCH (NIM / Nama / Jurusan)
    public List<Student> Search(string keyword)
    {
        using var connection = new SqlConnection(connectionString);
        string sql = SelectColumns +
                     "WHERE NIM LIKE @Keyword OR Nama LIKE @Keyword OR Jurusan LIKE @Keyword " +
                     "ORDER BY Id DESC";
        using var command = new SqlCommand(sql, connection);
        command.Parameters.Add("@Keyword", SqlDbType.VarChar, 120).Value = "%" + keyword + "%";

        connection.Open();
        return ReadStudents(command);
    }

    // INSERT
    public void Insert(Student student)
    {
        using var connection = new SqlConnection(connectionString);
        string sql =
            "INSERT INTO Students (NIM, Nama, Jurusan, Gender, Email) " +
            "VALUES (@NIM, @Nama, @Jurusan, @Gender, @Email)";
        using var command = new SqlCommand(sql, connection);
        AddStudentParameters(command, student);

        connection.Open();
        command.ExecuteNonQuery();
    }

    // UPDATE
    public void Update(Student student)
    {
        using var connection = new SqlConnection(connectionString);
        string sql =
            "UPDATE Students SET NIM=@NIM, Nama=@Nama, Jurusan=@Jurusan, " +
            "Gender=@Gender, Email=@Email WHERE Id=@Id";
        using var command = new SqlCommand(sql, connection);
        command.Parameters.Add("@Id", SqlDbType.Int).Value = student.Id;
        AddStudentParameters(command, student);

        connection.Open();
        command.ExecuteNonQuery();
    }

    // DELETE
    public void Delete(int id)
    {
        using var connection = new SqlConnection(connectionString);
        using var command = new SqlCommand("DELETE FROM Students WHERE Id=@Id", connection);
        command.Parameters.Add("@Id", SqlDbType.Int).Value = id;

        connection.Open();
        command.ExecuteNonQuery();
    }

    // Cek NIM kembar (excludeId = Id yang sedang diedit, 0 untuk data baru)
    public bool NimExists(string nim, int excludeId)
    {
        using var connection = new SqlConnection(connectionString);
        using var command = new SqlCommand(
            "SELECT COUNT(1) FROM Students WHERE NIM=@NIM AND Id<>@Id", connection);
        command.Parameters.Add("@NIM", SqlDbType.VarChar, 20).Value = nim;
        command.Parameters.Add("@Id", SqlDbType.Int).Value = excludeId;

        connection.Open();
        return Convert.ToInt32(command.ExecuteScalar()) > 0;
    }

    private static void AddStudentParameters(SqlCommand command, Student student)
    {
        // Tipe parameter ditulis eksplisit (VarChar) agar sesuai dengan kolom VARCHAR di tabel.
        command.Parameters.Add("@NIM", SqlDbType.VarChar, 20).Value = student.NIM;
        command.Parameters.Add("@Nama", SqlDbType.VarChar, 100).Value = student.Nama;
        command.Parameters.Add("@Jurusan", SqlDbType.VarChar, 100).Value = student.Jurusan;
        command.Parameters.Add("@Gender", SqlDbType.VarChar, 20).Value = student.Gender;
        command.Parameters.Add("@Email", SqlDbType.VarChar, 100).Value = student.Email ?? "";
    }

    // Satu-satunya tempat yang memetakan baris SQL ke objek Student
    // (di PDF kode ini ditulis dua kali: di GetAll dan Search).
    private static List<Student> ReadStudents(SqlCommand command)
    {
        var students = new List<Student>();
        using SqlDataReader reader = command.ExecuteReader();
        while (reader.Read())
        {
            students.Add(new Student
            {
                Id = Convert.ToInt32(reader["Id"]),
                NIM = reader["NIM"].ToString() ?? "",
                Nama = reader["Nama"].ToString() ?? "",
                Jurusan = reader["Jurusan"].ToString() ?? "",
                Gender = reader["Gender"].ToString() ?? "",
                Email = reader["Email"].ToString() ?? ""
            });
        }
        return students;
    }
}
