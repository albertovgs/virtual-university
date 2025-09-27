-- Verification script to ensure database is properly initialized
-- This script runs after all other initialization scripts

-- Verify database character set and collation
SELECT 
    SCHEMA_NAME as 'Database',
    DEFAULT_CHARACTER_SET_NAME as 'Character Set',
    DEFAULT_COLLATION_NAME as 'Collation'
FROM information_schema.SCHEMATA 
WHERE SCHEMA_NAME = 'sw15_update';

-- Verify tables were created
SELECT COUNT(*) as 'Total Tables' FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'sw15_update';

-- Verify admin user was created
SELECT 
    u.IDUser as 'User ID',
    u.email_user as 'Email',
    u.type_user as 'User Type',
    p.name_person as 'Name',
    p.lastname_person as 'Last Name'
FROM tb_users u 
JOIN tb_people p ON u.id_user = p.id_person 
WHERE u.type_user = 'Admin';

-- Verify classrooms were inserted
SELECT COUNT(*) as 'Total Classrooms' FROM tb_classrooms;

-- Show database initialization completion message
SELECT 'Database initialization completed successfully!' as 'Status';