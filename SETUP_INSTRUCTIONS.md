# Services Setup Instructions

## Database Setup Required

Since you're using an independent MySQL database, you need to run the SQL script to create the missing `categories` and `sections` tables.

### Step 1: Run the SQL Script

Execute the `create_categories_sections_tables.sql` file in your MySQL database:

```bash
# Option 1: Using MySQL command line
mysql -u your_username -p your_database_name < create_categories_sections_tables.sql

# Option 2: Using phpMyAdmin or similar tool
# - Open phpMyAdmin
# - Select your database
# - Go to SQL tab
# - Copy and paste the contents of create_categories_sections_tables.sql
# - Execute the script
```

### Step 2: Verify Tables Created

After running the script, verify that the following tables exist:
- `categories` (with initial data: Hair Cut, Hair Color, etc.)
- `sections` (with initial data: Men Hair Team, Women Hair Team, etc.)

### Step 3: Test the Services Page

1. Visit `/admin/services` in your application
2. The page should now load without 500 errors
3. You should see:
   - Services list (may be empty initially)
   - Filter dropdowns populated with categories and sections
   - "Manage Sections" button for section management
   - "Add Service" button for creating new services

### What This Fixes

- ✅ Resolves 500 error: `GET /admin/services/categories-and-sections/meta`
- ✅ Enables proper service categorization by sections and categories
- ✅ Provides section management interface
- ✅ Allows full CRUD operations on services
- ✅ Maintains referential integrity with foreign key constraints

### Features Added

1. **Section Management Interface**: 
   - Add, edit, delete sections
   - View services count per section
   - Prevent deletion of sections with associated services

2. **Complete Service CRUD**:
   - Create services with proper category and section assignment
   - Edit existing services
   - Delete services (with transaction checks)
   - Filter services by category, section, and status

3. **Data Integrity**:
   - Foreign key constraints ensure data consistency
   - Proper error handling and validation
   - Prevents orphaned records

The services page is now fully functional with comprehensive section management capabilities.