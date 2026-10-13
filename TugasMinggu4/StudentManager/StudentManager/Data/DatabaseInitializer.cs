using Microsoft.Data.SqlClient;

namespace StudentManager.Data;

/// <summary>
/// Membuat StudentDB, tabel Students, dan data sample jika belum ada.
/// Hasilnya sama dengan menjalankan Database/StudentDB.sql secara manual.
/// </summary>
public static class DatabaseInitializer
{
    public static void EnsureCreated()
    {
        using (var master = new SqlConnection(DbConfig.MasterConnectionString))
        {
            master.Open();
            using var createDb = new SqlCommand(
                "IF DB_ID('StudentDB') IS NULL CREATE DATABASE StudentDB;", master);
            createDb.ExecuteNonQuery();
        }

        using var connection = new SqlConnection(DbConfig.StudentDbConnectionString);
        connection.Open();

        const string createTable = @"
IF OBJECT_ID('dbo.Students', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.Students
    (
        Id      INT IDENTITY(1,1) PRIMARY KEY,
        NIM     VARCHAR(20)  NOT NULL,
        Nama    VARCHAR(100) NOT NULL,
        Jurusan VARCHAR(100) NOT NULL,
        Gender  VARCHAR(20)  NOT NULL,
        Email   VARCHAR(100)
    );

    INSERT INTO dbo.Students (NIM, Nama, Jurusan, Gender, Email) VALUES
    ('23001', 'Budi Santoso', 'Informatika',      'Laki-laki', 'budi@gmail.com'),
    ('23002', 'Siti Aminah',  'Sistem Informasi', 'Perempuan', 'siti@gmail.com'),
    ('23003', 'Andi Wijaya',  'Informatika',      'Laki-laki', 'andi@gmail.com'),
    ('23004', 'Rina Sari',    'Sistem Informasi', 'Perempuan', 'rina@gmail.com');
END";
        using var command = new SqlCommand(createTable, connection);
        command.ExecuteNonQuery();
    }
}
