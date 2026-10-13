using System.Windows;
using StudentManager.Data;

namespace StudentManager;

public partial class App : Application
{
    protected override void OnStartup(StartupEventArgs e)
    {
        // Pastikan database StudentDB, tabel Students, dan data sample ada
        // SEBELUM MainWindow dibuat (MainWindow langsung memuat data di constructor ViewModel).
        try
        {
            DatabaseInitializer.EnsureCreated();
        }
        catch (Exception ex)
        {
            MessageBox.Show(
                "Gagal menyiapkan database SQL Server LocalDB.\n\n" + ex.Message +
                "\n\nSolusi: pasang komponen 'SQL Server Express LocalDB' " +
                "(Visual Studio Installer > Modify > Individual components), " +
                "atau ganti nama server di Data/DbConfig.cs jika memakai SQL Server lain.",
                "Database Error",
                MessageBoxButton.OK,
                MessageBoxImage.Error);
            Shutdown();
            return;
        }

        base.OnStartup(e);
    }
}
