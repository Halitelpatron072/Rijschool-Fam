CREATE TABLE IF NOT EXISTS schedule (
    id INT AUTO_INCREMENT PRIMARY KEY,
    day_of_week TINYINT NOT NULL COMMENT '0 = Sunday, 1 = Monday, ..., 6 = Saturday',
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    lesson_type VARCHAR(50) NOT NULL COMMENT 'e.g., rijles, praktijkexamen, theorie',
    location VARCHAR(100),
    notes VARCHAR(255),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO schedule (day_of_week, start_time, end_time, lesson_type, location, notes) VALUES
(1, '09:00:00', '10:30:00', 'rijles', 'Rijschool Fam', 'Algemene rijles'),
(1, '11:00:00', '12:30:00', 'rijles', 'Rijschool Fam', 'Algemene rijles'),
(2, '09:00:00', '10:30:00', 'rijles', 'Rijschool Fam', 'Algemene rijles'),
(2, '14:00:00', '15:30:00', 'rijles', 'Rijschool Fam', 'Algemene rijles'),
(3, '10:00:00', '11:30:00', 'rijles', 'Rijschool Fam', 'Algemene rijles'),
(4, '09:00:00', '10:30:00', 'rijles', 'Rijschool Fam', 'Algemene rijles'),
(4, '13:00:00', '14:30:00', 'rijles', 'Rijschool Fam', 'Algemene rijles'),
(5, '09:00:00', '10:30:00', 'rijles', 'Rijschool Fam', 'Algemene rijles'),
(5, '11:00:00', '12:30:00', 'rijles', 'Rijschool Fam', 'Algemene rijles'),
(6, '10:00:00', '12:00:00', 'praktijkexamen', 'CBR', 'Examen leerling'),
(1, '15:00:00', '16:30:00', 'theorie', 'Rijschool Fam', 'Theoriecursus');
