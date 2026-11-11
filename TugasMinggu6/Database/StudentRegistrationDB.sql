-- =====================================================
-- Script database untuk Hands-On Lab 2
-- Jalankan di SSMS jika database dari Lab 1 belum ada
-- =====================================================

IF DB_ID('StudentRegistrationDB') IS NULL
    CREATE DATABASE StudentRegistrationDB;
GO

USE StudentRegistrationDB;
GO

IF OBJECT_ID('dbo.Students', 'U') IS NOT NULL DROP TABLE dbo.Students;
IF OBJECT_ID('dbo.Programs', 'U') IS NOT NULL DROP TABLE dbo.Programs;
GO

CREATE TABLE dbo.Programs
(
    ProgramId   INT IDENTITY(1,1) PRIMARY KEY,
    ProgramCode NVARCHAR(10)  NOT NULL UNIQUE,
    ProgramName NVARCHAR(100) NOT NULL,
    IsActive    BIT           NOT NULL DEFAULT 1
);
GO

CREATE TABLE dbo.Students
(
    StudentId   INT IDENTITY(1,1) PRIMARY KEY,
    NIM         NVARCHAR(20)  NOT NULL UNIQUE,
    Name        NVARCHAR(100) NOT NULL,
    ProgramId   INT           NOT NULL,
    BirthDate   DATE          NULL,
    Address     NVARCHAR(255) NULL,
    PhoneNumber NVARCHAR(20)  NULL,
    CreatedAt   DATETIME2     NOT NULL DEFAULT SYSDATETIME(),
    UpdatedAt   DATETIME2     NULL,
    CONSTRAINT FK_Students_Programs
        FOREIGN KEY (ProgramId) REFERENCES dbo.Programs(ProgramId)
);
GO

INSERT INTO dbo.Programs (ProgramCode, ProgramName) VALUES
('TI',  'Teknik Informatika'),
('SI',  'Sistem Informasi'),
('MJ',  'Manajemen'),
('EL',  'Teknik Elektro');
GO

INSERT INTO dbo.Students (NIM, Name, ProgramId, BirthDate, Address, PhoneNumber) VALUES
('20231001', 'Ahmad Rizki',   1, '2004-03-12', 'Surabaya',  '081234567801'),
('20231002', 'Siti Aisyah',   2, '2004-07-21', 'Sidoarjo',  '081234567802'),
('20231003', 'Budi Santoso',  1, '2003-11-05', 'Malang',    '081234567803'),
('20231004', 'Citra Lestari', 3, '2004-01-30', 'Kediri',    '081234567804'),
('20231005', 'Dimas Prayoga', 4, '2003-09-17', 'Gresik',    '081234567805');
GO

-- Verifikasi
SELECT s.StudentId, s.NIM, s.Name, p.ProgramName, s.BirthDate, s.PhoneNumber
FROM Students s
INNER JOIN Programs p ON s.ProgramId = p.ProgramId
ORDER BY s.StudentId;
GO
