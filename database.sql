Create table Alunos (
    Id int primary key auto_increment,
    Aluno varchar(200) NOT NULL,
    RA varchar(8) NOT NULL,
    Matriculado bit default 1
);

Create Table Materias(
    Id int primary key auto_increment,
    Materia varchar(60) NOT NULL,
    Disponivel bit default 1
);

create table Notas(
    Id int primary key auto_increment,
    Id_Materia int,
    Id_Aluno int,
    Constraint FK_NOTAS_ALUNOS Foreign key (Id_Aluno) References Alunos(Id),
    Constraint FK_NOTAS_Materias Foreign key (Id_Materia) References Materias(Id)
);
