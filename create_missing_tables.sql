-- Create categories table
CREATE TABLE `categories` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `description` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert categories data
INSERT INTO categories (id, name, description) VALUES
(1, 'Hair Care & Styling', 'Haircuts, washing, coloring, plaiting and treatments'),
(2, 'Nails & Hands', 'Manicure, pedicure, gel and nail enhancements'),
(3, 'Skin & Beauty', 'Facials, makeup and skincare'),
(4, 'Body & Relaxation', 'Massage, scrubs, waxing and foot care'),
(5, 'Packages & Specials', 'Bridal and bundled services'),
(6, 'Other Services', 'Custom or miscellaneous services');

-- Create sections table
CREATE TABLE `sections` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `description` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sections data
INSERT INTO sections (id, name, description) VALUES
(1, 'Men Hair Team', 'Barbers handling men haircuts and beard grooming'),
(2, 'Women Hair Team', 'Plaiting, styling, treatments for women'),
(3, 'Nails Team', 'Manicure, pedicure, gel and nail services'),
(4, 'Spa & Body Team', 'Massage, scrubbing, foot & leg care'),
(5, 'Makeup & Skin Team', 'Makeup, facials and skincare'),
(6, 'Packages Team', 'Bridal and combo services');

-- Add foreign key columns to services table if they don't exist
ALTER TABLE services 
ADD COLUMN IF NOT EXISTS category_id INT UNSIGNED NULL AFTER name,
ADD COLUMN IF NOT EXISTS section_id INT UNSIGNED NULL AFTER category_id;

-- Add foreign key constraints (optional, but recommended)
-- ALTER TABLE services 
-- ADD CONSTRAINT fk_services_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
-- ADD CONSTRAINT fk_services_section FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE SET NULL;