DROP DATABASE IF EXISTS sistema;
CREATE DATABASE sistema;
ALTER DATABASE sistema CHARSET = UTF8 COLLATE = utf8_general_ci;
USE sistema;

CREATE TABLE usuario(
id INTEGER NOT NULL AUTO_INCREMENT,
username VARCHAR(20) NOT NULL,
senha VARCHAR(256) NOT NULL,
email VARCHAR(40) NOT NULL,
nome VARCHAR(60) NOT NULL,
data_nascimento DATE NOT NULL,
telefone VARCHAR(15),
adm INTEGER NOT NULL,
PRIMARY KEY(id)
);

CREATE TABLE medico(
especialidade VARCHAR(40) NOT NULL,
crm INTEGER(7) NOT NULL,
endereco_comercial VARCHAR(60),
id_usuario INTEGER NOT NULL,
PRIMARY KEY(id_usuario)
);

CREATE TABLE paciente(
endereco_residencial VARCHAR(60),
id_usuario INTEGER NOT NULL,
PRIMARY KEY(id_usuario)
);

CREATE TABLE prontuario(
id INTEGER NOT NULL AUTO_INCREMENT,
id_paciente INTEGER,
id_medico INTEGER NOT NULL,
id_protocolo INTEGER NOT NULL,
valores TEXT,
PRIMARY KEY(id)
);

CREATE TABLE consulta(
id INTEGER NOT NULL AUTO_INCREMENT,
data DATE NOT NULL,
dados TEXT NOT NULL,
id_protocolo INTEGER NOT NULL,
id_prontuario INTEGER NOT NULL,
PRIMARY KEY(id)
);

CREATE TABLE exame(
id INTEGER NOT NULL AUTO_INCREMENT,
nome VARCHAR(40) NOT NULL,
data DATE NOT NULL,
laudo TEXT,
id_prontuario INTEGER NOT NULL,
PRIMARY KEY(id)
);

CREATE TABLE modulo(
id INTEGER NOT NULL AUTO_INCREMENT,
nome VARCHAR(40) NOT NULL,
campos TEXT NOT NULL,
PRIMARY KEY(id)
);

CREATE TABLE protocolo(
id INTEGER NOT NULL AUTO_INCREMENT,
nome VARCHAR(40) NOT NULL,
id_medico INTEGER,
PRIMARY KEY(id)
);

CREATE TABLE modulo_protocolo(
id_modulo INTEGER NOT NULL,
id_protocolo INTEGER NOT NULL,
PRIMARY KEY(id_modulo, id_protocolo)
);

CREATE TABLE registro(
id INTEGER NOT NULL AUTO_INCREMENT,
nome VARCHAR(40) NOT NULL,
tipo VARCHAR(10) NOT NULL,
data DATE NOT NULL,
horario VARCHAR(5) NOT NULL,
descricao TEXT,
id_paciente INTEGER NOT NULL,
PRIMARY KEY(id)
);

CREATE TABLE medicacao(
id INTEGER NOT NULL AUTO_INCREMENT,
remedio VARCHAR(40) NOT NULL,
intervalo INTEGER NOT NULL,
dosagem VARCHAR(40) NOT NULL,
período INTEGER,
data_inicial DATE,
descricao TEXT,
confirmacao BOOL NOT NULL,
id_paciente INTEGER NOT NULL,
PRIMARY KEY(id)
);

CREATE TABLE evento(
id INTEGER NOT NULL AUTO_INCREMENT,
descricao VARCHAR (140) NOT NULL,
tipo INTEGER NOT NULL,
data DATE NOT NULL,
horario VARCHAR(5) NOT NULL,
id_usuario INTEGER NOT NULL,
id_prontuario INTEGER,
PRIMARY KEY(id)
);

CREATE TABLE doenca(
id INTEGER NOT NULL AUTO_INCREMENT,
nome VARCHAR(50) NOT NULL,
descricao TEXT NOT NULL,
sintomas TEXT NOT NULL,
tratamento TEXT,
PRIMARY KEY(id)
);

CREATE TABLE remedio(
id INTEGER NOT NULL AUTO_INCREMENT,
nome VARCHAR(50) NOT NULL,
formula TEXT NOT NULL,
indicacoes TEXT,
contra_indicacoes TEXT,
efeitos_colaterais TEXT,
PRIMARY KEY(id)
);


ALTER TABLE medico ADD CONSTRAINT usuario_medico_fk FOREIGN KEY (id_usuario) REFERENCES usuario(id);
ALTER TABLE paciente ADD CONSTRAINT usuario_paciente_fk FOREIGN KEY (id_usuario) REFERENCES usuario(id);

ALTER TABLE prontuario ADD CONSTRAINT paciente_prontuario_fk FOREIGN KEY (id_paciente) REFERENCES paciente(id_usuario);
ALTER TABLE prontuario ADD CONSTRAINT medico_prontuario_fk FOREIGN KEY (id_medico) REFERENCES medico(id_usuario);
ALTER TABLE prontuario ADD CONSTRAINT protocolo_prontuario_fk FOREIGN KEY (id_protocolo) REFERENCES protocolo(id);

ALTER TABLE consulta ADD CONSTRAINT prontuario_consulta_fk FOREIGN KEY (id_prontuario) REFERENCES prontuario(id);

ALTER TABLE exame ADD CONSTRAINT prontuario_exame_fk FOREIGN KEY (id_prontuario) REFERENCES prontuario(id);

ALTER TABLE protocolo ADD CONSTRAINT medico_protocolo_fk FOREIGN KEY (id_medico) REFERENCES medico(id_usuario);

ALTER TABLE modulo_protocolo ADD CONSTRAINT modulo_modulo_protocolo_fk FOREIGN KEY(id_modulo) REFERENCES modulo(id);
ALTER TABLE modulo_protocolo ADD CONSTRAINT protocolo_modulo_protocolo_fk FOREIGN KEY(id_protocolo) REFERENCES protocolo(id) ON DELETE CASCADE;

ALTER TABLE registro ADD CONSTRAINT paciente_regisgtro_fk FOREIGN KEY (id_paciente) REFERENCES paciente(id_usuario);

ALTER TABLE medicacao ADD CONSTRAINT paciente_medicacao_fk FOREIGN KEY (id_paciente) REFERENCES paciente(id_usuario);

ALTER TABLE evento ADD CONSTRAINT usuario_evento_fk FOREIGN KEY (id_usuario) REFERENCES usuario(id);
ALTER TABLE evento ADD CONSTRAINT prontuario_evento_fk FOREIGN KEY (id_prontuario) REFERENCES prontuario(id);

INSERT INTO protocolo VALUES (1, "Novo Protocolo", NULL);
INSERT INTO modulo VALUES(1, "Dados Pessoais", "Nome:text;Idade:number;Data de Nascimento:date;Sexo:radio:Masculino-Feminino;Email:text;Telefone:text");
INSERT INTO modulo VALUES(2, "Motivo do Atendimento", "Anamnese:textarea;Possível Diagnóstico:text");
INSERT INTO modulo VALUES(3, "Antecedentes", "Alergias:text;Cirurgias:text;Vacinas:text;Doenças:text;Medicações:text;Fumante:radio:Sim-Nao;Alcoolatra:radio:Sim-Nao");
INSERT INTO modulo VALUES(4, "Histórico Familiar", "Doenças Hereditárias:text");
INSERT INTO modulo VALUES(5, "Exame Físico", "Altura:number;Peso:text;Pressão Arterial:text;Temperatura Corporal:text;Frequência Cardíaca:text");
INSERT INTO modulo_protocolo VALUES(1,1);
INSERT INTO modulo_protocolo VALUES(2,1);
INSERT INTO modulo_protocolo VALUES(3,1);

INSERT INTO usuario VALUES(1, "lucas", SHA2("12345", 256), "lucas@gmail.com", "Lucas Pons", "1999-11-11", "997151999", 0);
INSERT INTO usuario VALUES(2, "jose", SHA2("12345", 256), "jose@gmail.com", "José Silva", "1999-11-11", "997151999", 0);

INSERT INTO medico VALUES("Cardilogista", 68459, "Rua Gávea", 1);

INSERT INTO paciente VALUES("Rua das Laranjeiras", 2);
