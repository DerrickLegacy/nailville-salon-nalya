-- =====================================================
-- Nailville Login Performance Fix - SQL Script
-- Run this in phpMyAdmin if migration command fails
-- =====================================================

-- STEP 1: Backup sessions table (recommended)
-- CREATE TABLE sessions_backup AS SELECT * FROM sessions;

-- STEP 2: Check current indexes
SHOW INDEX FROM sessions;

-- STEP 3: Drop old single-column index (if exists)
SET @exist := (
    SELECT COUNT(*) 
    FROM information_schema.statistics 
    WHERE table_schema = DATABASE() 
    AND table_name = 'sessions' 
    AND index_name = 'sessions_last_activity_index'
);

SET @sqlstmt := IF(@exist > 0, 
    'ALTER TABLE sessions DROP INDEX sessions_last_activity_index', 
    'SELECT "Index does not exist" as message'
);

PREPARE stmt FROM @sqlstmt;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- STEP 4: Add composite index for better performance
ALTER TABLE sessions 
ADD INDEX sessions_last_activity_user_id_index (last_activity, user_id);

-- STEP 5: Clean up old sessions (older than 24 hours)
-- This will free up space and improve performance
DELETE FROM sessions 
WHERE last_activity < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 24 HOUR));

-- STEP 6: Verify changes
SELECT 
    'Sessions table optimized!' as status,
    COUNT(*) as active_sessions,
    FROM_UNIXTIME(MIN(last_activity)) as oldest_session,
    FROM_UNIXTIME(MAX(last_activity)) as newest_session
FROM sessions;

-- STEP 7: Show new indexes
SHOW INDEX FROM sessions WHERE Key_name LIKE '%last_activity%';

-- =====================================================
-- Results to expect:
-- - New index: sessions_last_activity_user_id_index
-- - Reduced number of sessions
-- - Login should be faster now
-- =====================================================
