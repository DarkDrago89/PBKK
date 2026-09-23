using System.Globalization;

namespace DataMahasiswa;

internal sealed class Mahasiswa
{
    public string Nim { get; init; } = string.Empty;
    public string Nama { get; init; } = string.Empty;
    public string Prodi { get; init; } = string.Empty;
    public double Ipk { get; init; }
}

internal static class Program
{
    private static readonly List<Mahasiswa> DaftarMahasiswa = new();

    private const string Reset = "\u001b[0m";
    private const string Cyan = "\u001b[96m";
    private const string Blue = "\u001b[94m";
    private const string Green = "\u001b[92m";
    private const string Yellow = "\u001b[93m";
    private const string Red = "\u001b[91m";
    private const string Dim = "\u001b[90m";

    private static void Main()
    {
        Console.OutputEncoding = System.Text.Encoding.UTF8;
        var pilihan = string.Empty;

        do
        {
            TampilkanMenu();
            pilihan = BacaInput("Pilih menu");

            switch (pilihan)
            {
                case "1":
                    TambahMahasiswa();
                    break;
                case "2":
                    TampilkanMahasiswa();
                    break;
                case "3":
                    CariMahasiswa();
                    break;
                case "4":
                    HapusMahasiswa();
                    break;
                case "5":
                    TampilkanPesan("Sampai jumpa. Terima kasih telah menggunakan aplikasi.", Green);
                    break;
                default:
                    TampilkanPesan("Pilihan tidak tersedia. Masukkan angka 1 sampai 5.", Red);
                    break;
            }

            if (pilihan != "5")
            {
                TungguEnter();
            }
        } while (pilihan != "5");
    }

    private static void TampilkanMenu()
    {
        Console.Clear();
        Console.ForegroundColor = ConsoleColor.DarkCyan;
        Console.WriteLine("╭────────────────────────────────────────────────────────────╮");
        Console.WriteLine("│                 SISTEM DATA MAHASISWA                     │");
        Console.WriteLine("│                 Kelola data dengan mudah                  │");
        Console.WriteLine("╰────────────────────────────────────────────────────────────╯");
        Console.ResetColor();
        Console.WriteLine();
        Console.WriteLine($"  {Cyan}┌─ MENU UTAMA ────────────────────────────────────────────┐{Reset}");
        TampilkanMenuItem("1", "Tambah mahasiswa", Green);
        TampilkanMenuItem("2", "Tampilkan semua data", Blue);
        TampilkanMenuItem("3", "Cari berdasarkan NIM", Yellow);
        TampilkanMenuItem("4", "Hapus data mahasiswa", Red);
        TampilkanMenuItem("5", "Keluar", Dim);
        Console.WriteLine($"  {Cyan}└─────────────────────────────────────────────────────────┘{Reset}");
        Console.WriteLine();
        Console.WriteLine($"  {Dim}Total data tersimpan: {DaftarMahasiswa.Count}{Reset}");
        Console.WriteLine();
    }

    private static void TampilkanMenuItem(string nomor, string teks, string warna)
    {
        Console.WriteLine($"  {warna}[{nomor}]{Reset}  {teks}");
    }

    private static void TambahMahasiswa()
    {
        TampilkanJudul("TAMBAH MAHASISWA", Green);
        var nim = BacaInputWajib("NIM");

        if (DaftarMahasiswa.Any(mahasiswa => mahasiswa.Nim.Equals(nim, StringComparison.OrdinalIgnoreCase)))
        {
            TampilkanPesan("NIM tersebut sudah terdaftar.", Red);
            return;
        }

        var mahasiswa = new Mahasiswa
        {
            Nim = nim,
            Nama = BacaInputWajib("Nama"),
            Prodi = BacaInputWajib("Program studi"),
            Ipk = BacaIpk()
        };

        DaftarMahasiswa.Add(mahasiswa);
        TampilkanPesan("Data mahasiswa berhasil ditambahkan.", Green);
    }

    private static void TampilkanMahasiswa()
    {
        TampilkanJudul("DAFTAR MAHASISWA", Blue);

        if (DaftarMahasiswa.Count == 0)
        {
            TampilkanPesan("Belum ada data mahasiswa.", Yellow);
            return;
        }

        Console.WriteLine($"  {Cyan}{"NIM",-14}{"Nama",-24}{"Program Studi",-24}{"IPK",5}{Reset}");
        Console.WriteLine($"  {Dim}{new string('─', 67)}{Reset}");

        foreach (var mahasiswa in DaftarMahasiswa)
        {
            Console.WriteLine($"  {mahasiswa.Nim,-14}{Potong(mahasiswa.Nama, 22),-24}{Potong(mahasiswa.Prodi, 22),-24}{mahasiswa.Ipk,5:F2}");
        }

        Console.WriteLine($"\n  {Dim}Menampilkan {DaftarMahasiswa.Count} data mahasiswa.{Reset}");
    }

    private static void CariMahasiswa()
    {
        TampilkanJudul("CARI MAHASISWA", Yellow);
        var nim = BacaInputWajib("Masukkan NIM");
        var mahasiswa = DaftarMahasiswa.FirstOrDefault(item => item.Nim.Equals(nim, StringComparison.OrdinalIgnoreCase));

        if (mahasiswa is null)
        {
            TampilkanPesan("Mahasiswa dengan NIM tersebut tidak ditemukan.", Red);
            return;
        }

        Console.WriteLine($"  {Green}Data ditemukan:{Reset}");
        Console.WriteLine($"  NIM            : {mahasiswa.Nim}");
        Console.WriteLine($"  Nama           : {mahasiswa.Nama}");
        Console.WriteLine($"  Program studi  : {mahasiswa.Prodi}");
        Console.WriteLine($"  IPK            : {mahasiswa.Ipk:F2}");
    }

    private static void HapusMahasiswa()
    {
        TampilkanJudul("HAPUS MAHASISWA", Red);
        var nim = BacaInputWajib("Masukkan NIM");
        var mahasiswa = DaftarMahasiswa.FirstOrDefault(item => item.Nim.Equals(nim, StringComparison.OrdinalIgnoreCase));

        if (mahasiswa is null)
        {
            TampilkanPesan("Data mahasiswa tidak ditemukan.", Red);
            return;
        }

        var konfirmasi = BacaInput($"Hapus data {mahasiswa.Nama}? (y/n)");
        if (konfirmasi.Equals("y", StringComparison.OrdinalIgnoreCase))
        {
            DaftarMahasiswa.Remove(mahasiswa);
            TampilkanPesan("Data mahasiswa berhasil dihapus.", Green);
        }
        else
        {
            TampilkanPesan("Penghapusan dibatalkan.", Yellow);
        }
    }

    private static double BacaIpk()
    {
        while (true)
        {
            var input = BacaInput("IPK (0.00 - 4.00)").Replace(',', '.');
            if (double.TryParse(input, NumberStyles.AllowDecimalPoint, CultureInfo.InvariantCulture, out var ipk) && ipk is >= 0 and <= 4)
            {
                return ipk;
            }

            TampilkanPesan("IPK harus berupa angka antara 0.00 sampai 4.00.", Red);
        }
    }

    private static string BacaInputWajib(string label)
    {
        while (true)
        {
            var input = BacaInput(label);
            if (!string.IsNullOrWhiteSpace(input))
            {
                return input.Trim();
            }

            TampilkanPesan("Input tidak boleh kosong.", Red);
        }
    }

    private static string BacaInput(string label)
    {
        Console.Write($"  {Cyan}{label,-22}: {Reset}");
        return Console.ReadLine() ?? string.Empty;
    }

    private static void TampilkanJudul(string judul, string warna)
    {
        Console.Clear();
        Console.WriteLine($"  {warna}╭─────────────────────────────────────────────────────────╮{Reset}");
        Console.WriteLine($"  {warna}│ {judul,-55} │{Reset}");
        Console.WriteLine($"  {warna}╰─────────────────────────────────────────────────────────╯{Reset}\n");
    }

    private static void TampilkanPesan(string pesan, string warna)
    {
        Console.WriteLine($"\n  {warna}>> {pesan}{Reset}");
    }

    private static void TungguEnter()
    {
        Console.WriteLine($"\n  {Dim}Tekan ENTER untuk kembali ke menu...{Reset}");
        Console.ReadLine();
    }

    private static string Potong(string teks, int panjang)
    {
        return teks.Length <= panjang ? teks : $"{teks[..(panjang - 3)]}...";
    }
}
