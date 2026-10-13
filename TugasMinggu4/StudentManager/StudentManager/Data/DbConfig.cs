namespace StudentManager.Data;

public static class DbConfig
{
    // Ganti nilai ini jika tidak memakai LocalDB, contoh:
    //   @"(localdb)\MSSQLLocalDB"  -> SQL Server LocalDB (default)
    //   @".\SQLEXPRESS"            -> SQL Server Express yang terpasang di PC
    //   "localhost"                -> SQL Server (Developer/Standard) default instance
    private const string Server = @"(localdb)\MSSQLLocalDB";

    // Dipakai untuk membuat database jika belum ada.
    public const string MasterConnectionString =
        "Server=" + Server + ";Database=master;Trusted_Connection=True;TrustServerCertificate=True;";

    // Dipakai oleh repository untuk operasi CRUD.
    public const string StudentDbConnectionString =
        "Server=" + Server + ";Database=StudentDB;Trusted_Connection=True;TrustServerCertificate=True;";
}
