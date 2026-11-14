CREATE DATABASE IF NOT EXISTS HelpDz
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

USE HelpDz;

CREATE TABLE Admins (
  admin_id INT PRIMARY KEY AUTO_INCREMENT,
  admin_name VARCHAR(35) NOT NULL,
  admin_last_name VARCHAR(35) NOT NULL,
  admin_email VARCHAR(60) NOT NULL,
  phone_number VARCHAR(10) NOT NULL,
  admin_password VARCHAR(255) NOT NULL,
  salt TEXT NOT NULL,
  pepper TEXT NOT NULL,
  OTP VARCHAR(7),
  admin_level ENUM('main_admin', 'sub_admin','non_admin') DEFAULT 'non_admin',
  CONSTRAINT admin_email UNIQUE (admin_email)
);

CREATE TABLE Users (
  user_id INT PRIMARY KEY AUTO_INCREMENT,
  user_name VARCHAR(35) NOT NULL,
  user_last_name VARCHAR(35) NOT NULL,
  user_password TEXT NOT NULL,
  user_email VARCHAR(60) NOT NULL,
  wilaya VARCHAR(35) NOT NULL,
  city VARCHAR(35) NOT NULL,
  district VARCHAR(35) NOT NULL,
  user_address VARCHAR(35) NOT NULL,
  phone_number VARCHAR(10) NOT NULL,
  user_r_pseudo VARCHAR(50) NOT NULL,
  user_l_pseudo VARCHAR(50) NOT NULL,
  parrain_pseudo VARCHAR(50) NOT NULL,
  parent_user_id INT,
  left_child_id INT,
  right_child_id INT,
  salt TEXT NOT NULL,
  pepper TEXT NOT NULL,
  bought BOOLEAN DEFAULT FALSE,
  is_v BOOLEAN DEFAULT FALSE,
  RIP BIGINT UNSIGNED NOT NULL,
  OTP VARCHAR(7),
  is_f BOOLEAN DEFAULT FALSE,
  virtual_money decimal(10,0) DEFAULT 0,
  CONSTRAINT unique_r_pseudo UNIQUE (user_r_pseudo),
  CONSTRAINT unique_l_pseudo UNIQUE (user_l_pseudo)
);

INSERT INTO Users (
  user_name, user_last_name, user_password, user_email, wilaya, city, district, user_address, phone_number, user_r_pseudo, user_l_pseudo, parrain_pseudo, salt, pepper, RIP
) VALUES (
  'HopeToHelpDz', '', '', '', '', '', '', '', '', 'helpdzd', 'helpdzg', '', '', '', 0
);

CREATE TABLE ValidatedInfo (
    v_i_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    v_i_s BOOLEAN DEFAULT 0,
    user_name VARCHAR(35) NOT NULL,
    user_last_name VARCHAR(35) NOT NULL,
    wilaya VARCHAR(35) NOT NULL,
    city VARCHAR(35) NOT NULL,
    district VARCHAR(35) NOT NULL,
    user_address VARCHAR(35) NOT NULL,
    phone_number VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);


CREATE TABLE DirectProfit (
  direct_profit_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  claimed_date TIMESTAMP,
  claimed_profit DECIMAL(10, 2) DEFAULT 0,
  paid ENUM('paid', 'not_paid') DEFAULT 'not_paid',
  is_v BOOLEAN DEFAULT FALSE,
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE IndirectProfit (
  indirect_profit_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  claimed_date TIMESTAMP,
  claimed_profit DECIMAL(10, 2) DEFAULT 0,
  paid ENUM('paid', 'not_paid') DEFAULT 'not_paid',
  is_v BOOLEAN DEFAULT FALSE, 
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE CProfit (
  c_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  c_amount DECIMAL(10, 2) DEFAULT 0,
  c_profit_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE PProfit (
  p_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  P_amount DECIMAL(10, 2) DEFAULT 0,
  P_profit_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE DProfit (
  d_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  d_amount DECIMAL(10, 2) DEFAULT 0,
  d_profit_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE ReclaimedProfits (
  reclaimed_id INT PRIMARY KEY AUTO_INCREMENT,
  root_pseudo VARCHAR(50) NOT NULL,
  descendant_pseudo VARCHAR(50) NOT NULL,
  UNIQUE KEY (root_pseudo, descendant_pseudo),
  FOREIGN KEY (root_pseudo) REFERENCES Users(user_r_pseudo),
  FOREIGN KEY (descendant_pseudo) REFERENCES Users(user_r_pseudo)
);

CREATE TABLE RipChangeRequests (
  request_id INT AUTO_INCREMENT PRIMARY KEY,
  user_pseudo VARCHAR(50) NOT NULL,
  new_rip BIGINT UNSIGNED NOT NULL,
  original_rip BIGINT UNSIGNED NOT NULL,
  request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
  FOREIGN KEY (user_pseudo) REFERENCES Users(user_r_pseudo)
);


CREATE TABLE Categories (
  category_id INT PRIMARY KEY AUTO_INCREMENT,
  category_name VARCHAR(50) NOT NULL
);

INSERT INTO Categories (category_name) VALUES
('Beauté'),
('Mode'),
('Technologie'),
('Alimentation'),
('Voyage'),
('Santé'),
('Sports'),
('Divertissement'),
('Maison'),
('Éducation'),
('Art'),
('Musique'),
('Livres & Littérature'),
('Automobiles'),
('Finance'),
('Animaux de Compagnie'),
('Plein Air'),
('Fitness'),
('Informatique');

CREATE TABLE Languages (
  language_id INT PRIMARY KEY AUTO_INCREMENT,
  language_name VARCHAR(50) NOT NULL
);

INSERT INTO Languages (language_name) VALUES
('Français'),
('Arabe'),
('Anglais');

CREATE TABLE Contacts (
  contact_id INT PRIMARY KEY AUTO_INCREMENT,
  contact_info VARCHAR(255) NOT NULL
);

CREATE TABLE Courses (
  course_id INT PRIMARY KEY AUTO_INCREMENT,
  course_img VARCHAR(255) NOT NULL,
  course_name VARCHAR(35) NOT NULL,
  price INT(6) NOT NULL,
  category_id INT,
  language_id INT,
  contact_id INT,
  FOREIGN KEY (contact_id) REFERENCES Contacts(contact_id),
  FOREIGN KEY (category_id) REFERENCES Categories(category_id),
  FOREIGN KEY (language_id) REFERENCES Languages(language_id)
);

CREATE TABLE CourseParts (
  part_id INT PRIMARY KEY AUTO_INCREMENT,
  course_id INT,
  part_title VARCHAR(255) NOT NULL,
  part_order INT,
  FOREIGN KEY (course_id) REFERENCES Courses(course_id)
);

CREATE TABLE CourseVideos (
  video_id INT PRIMARY KEY AUTO_INCREMENT,
  part_id INT,
  video_title VARCHAR(255) NOT NULL,
  video_url VARCHAR(255) NOT NULL,
  video_order INT,
  FOREIGN KEY (part_id) REFERENCES CourseParts(part_id)
);

CREATE TABLE OrderStatus (
  order_status_id INT PRIMARY KEY AUTO_INCREMENT,
  order_status_description VARCHAR(12) NOT NULL
);

INSERT INTO OrderStatus (order_status_description) VALUES
('En attente'),
('regarder'),
('Acheter');

CREATE TABLE Orders (
  order_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT,
  course_id INT,
  exp DATE,
  order_status_id INT,
  FOREIGN KEY (user_id) REFERENCES Users(user_id),
  FOREIGN KEY (course_id) REFERENCES Courses(course_id),
  FOREIGN KEY (order_status_id) REFERENCES OrderStatus(order_status_id)
);

CREATE TABLE PaymentImages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    is_v BOOLEAN DEFAULT 0,
    image_path VARCHAR(255),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);


CREATE TABLE TotalCourses (
  c_profit_id INT PRIMARY KEY AUTO_INCREMENT,
  c_profit DECIMAL(11, 2) DEFAULT 0,
  c_profit_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE Subscribers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sub_email VARCHAR(255) NOT NULL,
  subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT unique_sub_email UNIQUE (sub_email)
);

CREATE TABLE ContactSubmissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    email VARCHAR(60) NOT NULL,
    subject VARCHAR(30) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE SendedProfit (
  sended_profit_id INT PRIMARY KEY AUTO_INCREMENT,
  sender_id INT NOT NULL,
  receiver_id INT NOT NULL,
  amount DECIMAL(10, 2) NOT NULL,
  sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (sender_id) REFERENCES Users(user_id),
  FOREIGN KEY (receiver_id) REFERENCES Users(user_id)
);

CREATE TABLE Certifications (
  certification_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT,
  course_id INT,
  contact_id INT,
  FOREIGN KEY (contact_id) REFERENCES Contacts(contact_id),
  FOREIGN KEY (user_id) REFERENCES Users(user_id),
  FOREIGN KEY (course_id) REFERENCES Courses(course_id)
);

CREATE TABLE Notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(60) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL,
    target_type ENUM('all', 'specific') DEFAULT 'all',
    is_active BOOLEAN DEFAULT 1,
    admin_id INT,
    FOREIGN KEY (admin_id) REFERENCES Admins(admin_id)
);

CREATE TABLE NotificationTargets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    notification_id INT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (notification_id) REFERENCES Notifications(id),
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE UserNotifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    notification_id INT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (notification_id) REFERENCES Notifications(id),
    UNIQUE KEY (user_id, notification_id)
);











CREATE TABLE Projects (
    project_id INT PRIMARY KEY AUTO_INCREMENT,
    project_name VARCHAR(140) NOT NULL,
    legal_form VARCHAR(255) NOT NULL,
    project_idea VARCHAR(210) NOT NULL,
    project_owner VARCHAR(255) NOT NULL,
    owner_skills TEXT NOT NULL,
    partners DECIMAL,
    reason_for_project TEXT NOT NULL,
    project_location VARCHAR(255) NOT NULL,
    unique_features TEXT NOT NULL,
    added_value VARCHAR(140) NOT NULL,
    target_audience TEXT NOT NULL,
    technology_impact TEXT NOT NULL,
    marketing_strategy TEXT NOT NULL,
    employees_count INT,
    rental_cost DECIMAL,
    equipment_cost DECIMAL,
    equipment_details TEXT,
    additional_expenses DECIMAL,
    owner_national_id VARCHAR(50) NOT NULL,
    vl BOOLEAN DEFAULT FALSE,
    display_location ENUM('project', 'drow', 'both') DEFAULT 'project',
    partnership_terms ENUM('limited', 'unlimited') NOT NULL,
    partner_interest_rate DECIMAL(3),
    total_project_cost DECIMAL NOT NULL,
    number_of_partners INT NOT NULL,
    participation_amount_per_person DECIMAL NOT NULL, 
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
  );

CREATE TABLE Partners (
    partner_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(40) NOT NULL,
    last_name VARCHAR(40) NOT NULL,
    phone_number VARCHAR(11) NOT NULL,
    email VARCHAR(150) NOT NULL,
    personal_address TEXT NOT NULL,
    accp BOOLEAN DEFAULT FALSE,
    user_id INT NULL,
    project_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (project_id) REFERENCES Projects(project_id)
);

CREATE TABLE Paid (
    paid_id INT PRIMARY KEY AUTO_INCREMENT,
    is_d BOOLEAN DEFAULT FALSE,
    is_p BOOLEAN DEFAULT FALSE,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE Drow (
    drow_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    project_id INT NOT NULL,
    FOREIGN KEY (project_id) REFERENCES Projects(project_id),
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);