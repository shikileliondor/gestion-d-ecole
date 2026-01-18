# TODO: Generate Laravel Migrations for ERP School System

## Step 1: Enhance school migration ✅
- Translate to English
- Enrich with additional fields (website, postal_code, etc.)
- Add indexes and constraints

## Step 2: Enhance academic_years migration ✅
- Translate to English
- Enrich with additional fields (description, status)
- Add indexes

## Step 3: Create roles and permissions tables
- Create roles table
- Create permissions table
- Create role_user pivot table
- Create permission_role pivot table

## Step 4: Enrich users table
- Add role_id foreign key
- Add additional fields (phone, avatar, etc.)
- Add indexes

## Step 5: Create students-related tables
- Create students table (enriched)
- Create parents table
- Create student_parents table
- Create student_classes table
- Create student_documents table

## Step 6: Create staff-related tables
- Create staff table
- Create staff_documents table

## Step 7: Create organization tables
- Create classes table
- Create subjects table
- Create class_subjects table
- Create timetables table
- Create timetable_slots table

## Step 8: Create results tables
- Create assessments table
- Create grades table
- Create bulletins table
- Create appreciations table
- Create rankings table

## Step 9: Create finance tables
- Create fees table
- Create fee_classes table
- Create payments table
- Create payment_history table
- Create receipts table

## Step 10: Create document tables
- Create documents table
- Create uploads table
- Create downloads table

## Step 11: Create communication tables
- Create messages table
- Create notifications table
- Create sms_logs table
- Create whatsapp_logs table

## Step 12: Create asset tables
- Create assets table
- Create asset_categories table
- Create asset_movements table

## Step 13: Create tracking tables
- Create reports table
- Create activity_logs table

## Step 14: Create settings table
- Create settings table

## Final Step: Verify all migrations
- Check for consistency, foreign keys, indexes
