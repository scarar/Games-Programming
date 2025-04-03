#!/bin/bash

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}====================================================${NC}"
echo -e "${YELLOW}           PHP Application Test Suite                ${NC}"
echo -e "${YELLOW}====================================================${NC}"

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo -e "${RED}Error: PHP is not installed.${NC}"
    echo "Please install PHP and try again."
    exit 1
fi

# 1. PHP Syntax Check
echo -e "\n${YELLOW}Running PHP Syntax Check...${NC}"
php test_syntax.php
SYNTAX_RESULT=$?

# 2. Check for common security issues
echo -e "\n${YELLOW}Checking for common security issues...${NC}"

# Check for eval() usage
echo -e "\nChecking for eval() usage:"
EVAL_COUNT=$(grep -r "eval(" --include="*.php" . | wc -l)
if [ $EVAL_COUNT -gt 0 ]; then
    echo -e "${RED}Found $EVAL_COUNT instances of eval() - This is potentially dangerous!${NC}"
    grep -r "eval(" --include="*.php" . | head -5
else
    echo -e "${GREEN}No eval() usage found.${NC}"
fi

# Check for mysql_* functions (deprecated)
echo -e "\nChecking for deprecated mysql_* functions:"
MYSQL_COUNT=$(grep -r "mysql_" --include="*.php" . | grep -v "mysqli" | wc -l)
if [ $MYSQL_COUNT -gt 0 ]; then
    echo -e "${RED}Found $MYSQL_COUNT instances of deprecated mysql_* functions!${NC}"
    grep -r "mysql_" --include="*.php" . | grep -v "mysqli" | head -5
else
    echo -e "${GREEN}No deprecated mysql_* functions found.${NC}"
fi

# Check for SQL injection vulnerabilities
echo -e "\nChecking for potential SQL injection vulnerabilities:"
SQL_VULN_COUNT=$(grep -r "\$_" --include="*.php" . | grep -E "SELECT|INSERT|UPDATE|DELETE" | wc -l)
if [ $SQL_VULN_COUNT -gt 0 ]; then
    echo -e "${YELLOW}Found $SQL_VULN_COUNT potential SQL injection vulnerabilities.${NC}"
    echo -e "${YELLOW}Manual review recommended for these files:${NC}"
    grep -r "\$_" --include="*.php" . | grep -E "SELECT|INSERT|UPDATE|DELETE" | cut -d: -f1 | sort | uniq | head -10
else
    echo -e "${GREEN}No obvious SQL injection vulnerabilities found.${NC}"
fi

# 3. Check for file permissions
echo -e "\n${YELLOW}Checking file permissions...${NC}"
WORLD_WRITABLE=$(find . -type f -perm -o=w | wc -l)
if [ $WORLD_WRITABLE -gt 0 ]; then
    echo -e "${RED}Found $WORLD_WRITABLE world-writable files!${NC}"
    find . -type f -perm -o=w | head -5
else
    echo -e "${GREEN}No world-writable files found.${NC}"
fi

# 4. Check database configuration
echo -e "\n${YELLOW}Checking database configuration...${NC}"
DB_FILES=$(grep -l "database" --include="*.php" --include="*.sql" . | wc -l)
echo -e "Found $DB_FILES files with database references."

# Check for hardcoded credentials
HARDCODED_CREDS=$(grep -E "password|user|username" --include="*.php" . | grep -E "=['\"][^'\"]+['\"]" | wc -l)
if [ $HARDCODED_CREDS -gt 0 ]; then
    echo -e "${YELLOW}Found $HARDCODED_CREDS potential hardcoded credentials.${NC}"
    echo -e "${YELLOW}Consider using environment variables instead.${NC}"
else
    echo -e "${GREEN}No obvious hardcoded credentials found.${NC}"
fi

# 5. Summary
echo -e "\n${YELLOW}====================================================${NC}"
echo -e "${YELLOW}                    SUMMARY                         ${NC}"
echo -e "${YELLOW}====================================================${NC}"

if [ $SYNTAX_RESULT -eq 0 ]; then
    echo -e "${GREEN}✅ PHP Syntax: All files passed${NC}"
else
    echo -e "${RED}❌ PHP Syntax: Some files have errors${NC}"
fi

if [ $EVAL_COUNT -eq 0 ] && [ $MYSQL_COUNT -eq 0 ] && [ $SQL_VULN_COUNT -eq 0 ]; then
    echo -e "${GREEN}✅ Security Check: No major issues found${NC}"
else
    echo -e "${YELLOW}⚠️ Security Check: Some potential issues found${NC}"
fi

if [ $WORLD_WRITABLE -eq 0 ]; then
    echo -e "${GREEN}✅ File Permissions: No issues found${NC}"
else
    echo -e "${RED}❌ File Permissions: Some files have insecure permissions${NC}"
fi

echo -e "${YELLOW}====================================================${NC}"

# Exit with error code if any tests failed
if [ $SYNTAX_RESULT -ne 0 ] || [ $EVAL_COUNT -gt 0 ] || [ $MYSQL_COUNT -gt 0 ] || [ $WORLD_WRITABLE -gt 0 ]; then
    exit 1
else
    exit 0
fi