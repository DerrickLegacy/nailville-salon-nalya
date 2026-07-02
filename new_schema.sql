-- Payroll Management Module Schema Additions
-- Generated for Nailville Salon Nalya

-- Update employees table to add payroll-related fields
ALTER TABLE employees
ADD COLUMN payroll_type ENUM('commission', 'fixed', 'hybrid') DEFAULT 'commission' AFTER work_location,
ADD COLUMN commission_rate DECIMAL(5,2) DEFAULT 60.00 AFTER payroll_type;

-- Payroll Runs table (one record per employee per payroll period)
CREATE TABLE IF NOT EXISTS payroll_runs (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    employee_id BIGINT(20) UNSIGNED NOT NULL,
    payroll_month DATE NOT NULL,
    payroll_type ENUM('commission', 'fixed', 'hybrid') NOT NULL,
    total_sales DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    commission_rate DECIMAL(5,2) NOT NULL DEFAULT 60.00,
    gross_salary DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    total_deductions DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    net_salary DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    status ENUM('draft', 'pending_approval', 'approved', 'paid', 'cancelled', 'reversed') DEFAULT 'draft',
    notes TEXT,
    created_by BIGINT(20) UNSIGNED NOT NULL,
    updated_by BIGINT(20) UNSIGNED DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
    PRIMARY KEY (id),
    KEY payroll_runs_employee_id_foreign (employee_id),
    KEY payroll_runs_payroll_month_index (payroll_month),
    KEY payroll_runs_status_index (status),
    CONSTRAINT payroll_runs_employee_id_foreign FOREIGN KEY (employee_id) REFERENCES employees (employee_id) ON DELETE CASCADE,
    CONSTRAINT payroll_runs_created_by_foreign FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT payroll_runs_updated_by_foreign FOREIGN KEY (updated_by) REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payroll Deductions table
CREATE TABLE IF NOT EXISTS payroll_deductions (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    payroll_run_id BIGINT(20) UNSIGNED NOT NULL,
    deduction_name VARCHAR(100) NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    reason TEXT,
    entered_by BIGINT(20) UNSIGNED NOT NULL,
    notes TEXT,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
    PRIMARY KEY (id),
    KEY payroll_deductions_payroll_run_id_foreign (payroll_run_id),
    KEY payroll_deductions_entered_by_foreign (entered_by),
    CONSTRAINT payroll_deductions_payroll_run_id_foreign FOREIGN KEY (payroll_run_id) REFERENCES payroll_runs (id) ON DELETE CASCADE,
    CONSTRAINT payroll_deductions_entered_by_foreign FOREIGN KEY (entered_by) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Salary Advances table
CREATE TABLE IF NOT EXISTS salary_advances (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    employee_id BIGINT(20) UNSIGNED NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    reason TEXT,
    request_date DATE NOT NULL,
    approved_by BIGINT(20) UNSIGNED DEFAULT NULL,
    approval_date DATE DEFAULT NULL,
    status ENUM('pending', 'approved', 'rejected', 'partially_recovered', 'recovered') DEFAULT 'pending',
    remaining_balance DECIMAL(12,2) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
    PRIMARY KEY (id),
    KEY salary_advances_employee_id_foreign (employee_id),
    KEY salary_advances_approved_by_foreign (approved_by),
    KEY salary_advances_status_index (status),
    CONSTRAINT salary_advances_employee_id_foreign FOREIGN KEY (employee_id) REFERENCES employees (employee_id) ON DELETE CASCADE,
    CONSTRAINT salary_advances_approved_by_foreign FOREIGN KEY (approved_by) REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Advance Recovery Transactions table
CREATE TABLE IF NOT EXISTS advance_recoveries (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    salary_advance_id BIGINT(20) UNSIGNED NOT NULL,
    payroll_run_id BIGINT(20) UNSIGNED DEFAULT NULL,
    amount_recovered DECIMAL(12,2) NOT NULL,
    recovery_date DATE NOT NULL,
    recovered_by BIGINT(20) UNSIGNED NOT NULL,
    notes TEXT,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
    PRIMARY KEY (id),
    KEY advance_recoveries_salary_advance_id_foreign (salary_advance_id),
    KEY advance_recoveries_payroll_run_id_foreign (payroll_run_id),
    KEY advance_recoveries_recovered_by_foreign (recovered_by),
    CONSTRAINT advance_recoveries_salary_advance_id_foreign FOREIGN KEY (salary_advance_id) REFERENCES salary_advances (id) ON DELETE CASCADE,
    CONSTRAINT advance_recoveries_payroll_run_id_foreign FOREIGN KEY (payroll_run_id) REFERENCES payroll_runs (id) ON DELETE SET NULL,
    CONSTRAINT advance_recoveries_recovered_by_foreign FOREIGN KEY (recovered_by) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payroll Payments table
CREATE TABLE IF NOT EXISTS payroll_payments (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    payroll_run_id BIGINT(20) UNSIGNED NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    payment_method ENUM('cash', 'mobile_money', 'bank_transfer', 'cheque', 'other') NOT NULL,
    transaction_reference VARCHAR(255) DEFAULT NULL,
    payment_date DATE NOT NULL,
    paid_by BIGINT(20) UNSIGNED NOT NULL,
    notes TEXT,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
    PRIMARY KEY (id),
    KEY payroll_payments_payroll_run_id_foreign (payroll_run_id),
    KEY payroll_payments_paid_by_foreign (paid_by),
    CONSTRAINT payroll_payments_payroll_run_id_foreign FOREIGN KEY (payroll_run_id) REFERENCES payroll_runs (id) ON DELETE CASCADE,
    CONSTRAINT payroll_payments_paid_by_foreign FOREIGN KEY (paid_by) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payroll Adjustments table
CREATE TABLE IF NOT EXISTS payroll_adjustments (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    payroll_run_id BIGINT(20) UNSIGNED NOT NULL,
    adjustment_amount DECIMAL(12,2) NOT NULL,
    reason TEXT NOT NULL,
    adjustment_date DATE NOT NULL,
    recorded_by BIGINT(20) UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
    PRIMARY KEY (id),
    KEY payroll_adjustments_payroll_run_id_foreign (payroll_run_id),
    KEY payroll_adjustments_recorded_by_foreign (recorded_by),
    CONSTRAINT payroll_adjustments_payroll_run_id_foreign FOREIGN KEY (payroll_run_id) REFERENCES payroll_runs (id) ON DELETE CASCADE,
    CONSTRAINT payroll_adjustments_recorded_by_foreign FOREIGN KEY (recorded_by) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payroll Audit Logs table
CREATE TABLE IF NOT EXISTS payroll_audit_logs (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    payroll_run_id BIGINT(20) UNSIGNED NOT NULL,
    action VARCHAR(100) NOT NULL,
    old_value TEXT,
    new_value TEXT,
    performed_by BIGINT(20) UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (id),
    KEY payroll_audit_logs_payroll_run_id_foreign (payroll_run_id),
    KEY payroll_audit_logs_performed_by_foreign (performed_by),
    CONSTRAINT payroll_audit_logs_payroll_run_id_foreign FOREIGN KEY (payroll_run_id) REFERENCES payroll_runs (id) ON DELETE CASCADE,
    CONSTRAINT payroll_audit_logs_performed_by_foreign FOREIGN KEY (performed_by) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

alter table `payroll_audit_logs` add COLUMN updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP();
