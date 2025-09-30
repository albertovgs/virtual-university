-- Database initialization script for Docker MySQL container
-- This script is automatically executed when the MySQL container starts for the first time

-- Set character set and collation for the database
ALTER DATABASE sw15_update CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
USE sw15_update;

-- Create tables with proper character set
CREATE TABLE tb_people(
    id_person INT AUTO_INCREMENT PRIMARY KEY,
    name_person varchar(60) not null,
    lastname_person varchar(60) not null,
    birthday_person DATE not null,
    gender_person enum("M","F") not null,
    creation_date_person timestamp not null default CURRENT_TIMESTAMP,
    update_date_person timestamp not null default CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

insert into tb_people values (1, 'Admin', 'Learning', '2002/03/30', 'F', default, default);

CREATE TABLE tb_users(
    id_user INT NOT NULL,  
    IDUser INT NOT NULL,
    email_user varchar(120) UNIQUE not null,
    img_user varchar(255),
    password_user varchar(255),
    password_tem_user varchar(255) default null,
    force_change_user enum("Y","N") not null default "Y",
    status_user ENUM("Active","Inactive") not null default "Active",
    type_user enum("Studen","Teacher","Cordi","Admin"),
    creation_date_user timestamp not null default CURRENT_TIMESTAMP,
    update_date_user timestamp not null default CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES tb_people(id_person)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

insert into tb_users values (1,10000001, 'admin@learning.edu','/resources/dist/img/user_boy_one.webp','Hacker', default,"N",default,"Admin",default, default);

CREATE TABLE tb_majors(
    id_major INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name_major VARCHAR(60) NOT NULL,
    desc_major TEXT NOT NULL,
    clave_major VARCHAR(5) NOT NULL,
    status_major ENUM("Active","Inactive") not null default "Active",
    cordi_major INT NOT NULL,
    FOREIGN KEY (cordi_major) REFERENCES tb_users(id_user)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE tb_groups(
    id_group INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    clave_group VARCHAR(10) NOT NULL,
    major_group INT NOT NULL,
    status_group ENUM("Active","Inactive") not null default "Active",
    nmb_students INT NOT NULL,
    last_quarter varchar(25),
    FOREIGN KEY (major_group) REFERENCES tb_majors(id_major)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE tb_students(
    id_student INT NOT NULL,
    major_student INT NOT NULL,
    group_student VARCHAR(8) NOT NULL,
    FOREIGN KEY (id_student) REFERENCES tb_users(id_user),
    FOREIGN KEY (major_student) REFERENCES tb_majors(id_major)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE tb_classrooms(
    id_classroom INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name_classroom VARCHAR(60) NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

insert into tb_classrooms values(null,"Lab-1");
insert into tb_classrooms values(null,"Lab-2");
insert into tb_classrooms values(null,"Lab-3");
insert into tb_classrooms values(null,"Lab-4");
insert into tb_classrooms values(null,"Class Room-1");
insert into tb_classrooms values(null,"Class Room-2");
insert into tb_classrooms values(null,"Class Room-3");
insert into tb_classrooms values(null,"Class Room-4");
insert into tb_classrooms values(null,"Class Room-5");
insert into tb_classrooms values(null,"Class Room-6");

CREATE TABLE tb_classes(
    id_class INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name_class VARCHAR(60) NOT NULL,
    clave_class VARCHAR(60) NOT NULL UNIQUE,
    lab_class INT NOT NULL,
    class_part ENUM("First","Second") not null default "First",
    FOREIGN KEY (lab_class) REFERENCES tb_classrooms(id_classroom)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE tb_class_rates(
    id_class_rate INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    be_rate INT(2),
    do_rate INT(2),
    know_rate INT(2),
    rate INT NOT NULL,
    fk_class INT NOT NULL,
    FOREIGN KEY (fk_class) REFERENCES tb_classes(id_class)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE tb_periods(
    id_period INT PRIMARY KEY AUTO_INCREMENT,
    name_period VARCHAR(60) NOT NULL,
    start_date_period DATE NOT NULL,
    end_date_period DATE NOT NULL,
    type_period ENUM("Past","Current") 
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE tb_group_Pro_Class(
    id_gpc INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    fk_profesor INT NOT NULL,
    fk_class INT NOT NULL,
    fk_group INT NOT NULL, 
    fk_period INT NOT NULL, 
    FOREIGN KEY (fk_profesor) REFERENCES tb_users(id_user),
    FOREIGN KEY (fk_class) REFERENCES tb_classes(id_class),
    FOREIGN KEY (fk_group) REFERENCES tb_groups(id_group),
    FOREIGN KEY (fk_period) REFERENCES tb_periods(id_period)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE tb_schedules(
    id_schedule INT PRIMARY KEY AUTO_INCREMENT,
    day_schedule ENUM('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') NOT NULL,
    start_schedule TIME NOT NULL,
    end_schedule TIME NOT NULL,
    fk_period INT NOT NULL,
    fk_class INT NOT NULL,
    fk_classroom INT NOT NULL,
    FOREIGN KEY (fk_period) REFERENCES tb_periods(id_period),
    FOREIGN KEY (fk_class) REFERENCES tb_classes(id_class),
    FOREIGN KEY (fk_classroom) REFERENCES tb_classrooms(id_classroom)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE tb_classworks(
    id_classwork INT PRIMARY KEY AUTO_INCREMENT,
    title_classwork VARCHAR(60) NOT NULL,
    content_classwork TEXT NOT NULL,
    type_classwork VARCHAR(10),
    part_classwork ENUM("First","Second") not null default "First",
    status_classwork ENUM("Active","Inactive") not null default "Active",
    fk_gpc INT NOT NULL,
    date_end_classwork DATE,
    time_end_classwork TIME,
    FOREIGN KEY(fk_gpc) REFERENCES tb_group_Pro_Class(id_gpc)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE tb_std_classworks(
    id_std_classwork INT PRIMARY KEY AUTO_INCREMENT,
    calf_classwork FLOAT(6) NOT NULL,
    file_classwork VARCHAR(250),
    fk_student INT NOT NULL,
    fk_classwork INT NOT NULL,
    deliver_classwork timestamp not null default CURRENT_TIMESTAMP,
    FOREIGN KEY(fk_student) REFERENCES tb_students(id_student),
    FOREIGN KEY(fk_classwork) REFERENCES tb_classworks(id_classwork)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE tb_std_cls_clf(
    id_std_cls_clf INT PRIMARY KEY AUTO_INCREMENT,
    calf_f_class FLOAT(6) NOT NULL,
    calf_s_class FLOAT(6) NOT NULL,
    calf_class FLOAT(6) NOT NULL,
    fk_student INT NOT NULL,
    fk_class INT NOT NULL,
    FOREIGN KEY(fk_student) REFERENCES tb_students(id_student),
    FOREIGN KEY(fk_class) REFERENCES tb_classes(id_class)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE tb_periods_major(
    id_period_major INT PRIMARY KEY AUTO_INCREMENT,
    fk_major INT NOT NULL,
    fk_period INT NOT NULL,
    FOREIGN KEY (fk_major) REFERENCES tb_majors(id_major),
    FOREIGN KEY (fk_period) REFERENCES tb_periods(id_period)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE tb_periods_groups(
    id_period_group INT PRIMARY KEY AUTO_INCREMENT,
    fk_group INT NOT NULL,
    fk_period INT NOT NULL,
    FOREIGN KEY (fk_group) REFERENCES tb_groups(id_group),
    FOREIGN KEY (fk_period) REFERENCES tb_periods(id_period)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE tb_advertisements(
    id_advertisement INT(30) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title_advertisement VARCHAR(120) Not Null,
    content_advertisement TEXT Not null,
    img_path_advertisement VARCHAR(300),
    vid_path_advertisement VARCHAR(300),
    doc_path_advertisement VARCHAR(300),
    show_to_advertisement VARCHAR(10),
    status_advertisement enum('Active','Inactive') NOT NULL DEFAULT 'Active',
    fk_user_advertisement INT NOT NULL,
    creation_date_advertisement TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    finish_date_advertisement TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_date_advertisement TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (fk_user_advertisement) REFERENCES tb_users(id_user)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE tb_comments(
    id_comment INT(30) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    content_comment TEXT NOT NULL,
    status_comment enum('Active','Inactive') NOT NULL DEFAULT 'Active',
    fk_user_comment INT NOT NULL,
    creation_date_comment TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_date_comment TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (fk_user_comment) REFERENCES tb_users(id_user)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE tb_advertisements_comments(
    id_ad_comment INT(30) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    fk_ad INT NOT NULL,
    fk_comment INT NOT NULL,
    FOREIGN KEY (fk_ad) REFERENCES tb_advertisements(id_advertisement),
    FOREIGN KEY (fk_comment) REFERENCES tb_comments(id_comment)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE tb_classwork_comments(
    id_cw_comment INT(30) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    fk_cw INT NOT NULL,
    fk_comment INT NOT NULL,
    FOREIGN KEY (fk_cw) REFERENCES tb_std_classworks(id_std_classwork),
    FOREIGN KEY (fk_comment) REFERENCES tb_comments(id_comment)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE tb_requests(
    id_request INT PRIMARY KEY AUTO_INCREMENT,
    title_request VARCHAR(60) NOT NULL,
    request TEXT NOT NULL,
    status_request ENUM("Accepted","Rejected","Pending") DEFAULT "Pending",
    id_user_request INT NOT NULL,
    FOREIGN KEY(id_user_request) REFERENCES tb_users(id_user)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;