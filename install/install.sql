CREATE TABLE UserType(
    id    int (11) Auto_increment  NOT NULL,
    label Varchar (255) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE Employee(
    id          	int (11) Auto_increment  NOT NULL,
    login       	Varchar (255) NOT NULL,
    password    	Varchar (255) NOT NULL,
    firstname   	Varchar (255) NOT NULL,
    lastname    	Varchar (255) NOT NULL,
    email       	Varchar (25) NOT NULL,
    id_UserType 	Int NOT NULL,
    id_Superieur	Int,
    PRIMARY KEY (id)
);

CREATE TABLE Service(
    id    int (11) Auto_increment  NOT NULL,
    label Varchar (255) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE StatusConge(
    id    int (11) Auto_increment  NOT NULL,
    label Varchar (255) NOT NULL,
    PRIMARY KEY (id )
);

CREATE TABLE TypeConge(
    id    int (11) Auto_increment  NOT NULL,
    label Varchar (255) NOT NULL,
    PRIMARY KEY (id )
);

CREATE TABLE Solde(
    annee        int (4) NOT NULL,
    solde        Float NOT NULL,
    id_Employee  Int NOT NULL,
    id_TypeConge Int NOT NULL,
    PRIMARY KEY (id_Employee ,id_TypeConge, annee)
);

CREATE TABLE appartenir(
    id_Service  Int NOT NULL,
    id_Employee Int NOT NULL,
    PRIMARY KEY (id_Service ,id_Employee)
);


CREATE TABLE Conge(
	id				int(11) Auto_increment NOT NULL,
	debut_t			Varchar (255) NOT NULL,
	fin_t			Varchar (255) NOT NULL,
	id_StatusConge	Int NOT NULL,
	id_Employee 	Int NOT NULL,
	id_TypeConge 	Int NOT NULL,
	PRIMARY KEY (id)
);

ALTER TABLE Employee ADD UNIQUE (login);
ALTER TABLE Employee ADD UNIQUE (email);
ALTER TABLE Employee ADD CONSTRAINT FK_Employee_id_UserType FOREIGN KEY (id_UserType) REFERENCES UserType(id);
ALTER TABLE Employee ADD CONSTRAINT FK_Employee_id_Superieur FOREIGN KEY (id_Superieur) REFERENCES Employee(id);
ALTER TABLE Solde ADD CONSTRAINT FK_Solde_id_Employee FOREIGN KEY (id_Employee) REFERENCES Employee(id);
ALTER TABLE Solde ADD CONSTRAINT FK_Solde_id_TypeConge FOREIGN KEY (id_TypeConge) REFERENCES TypeConge(id);
ALTER TABLE appartenir ADD CONSTRAINT FK_appartenir_id_Service FOREIGN KEY (id_Service) REFERENCES Service(id);
ALTER TABLE appartenir ADD CONSTRAINT FK_appartenir_id_Employee FOREIGN KEY (id_Employee) REFERENCES Employee(id);
ALTER TABLE Conge ADD CONSTRAINT FK_Conge_id_StatusConge FOREIGN KEY (id_StatusConge) REFERENCES StatusConge(id);
ALTER TABLE Conge ADD CONSTRAINT FK_Conge_id_Employee FOREIGN KEY (id_Employee) REFERENCES Employee(id);
ALTER TABLE Conge ADD CONSTRAINT FK_Conge_id_TypeConge FOREIGN KEY (id_TypeConge) REFERENCES TypeConge(id);
