Payroll Management Module Requirements
Module Overview

The Payroll Management module shall manage all employee salary processing within the salon.

The system shall support multiple payroll models because not all staff are paid the same way.

Supported payroll types include:

Commission Based Payroll
Fixed Monthly Salary
Hybrid Salary (optional future support)

Each employee shall have one payroll type assigned.

Commission-Based Payroll

Certain employees are paid using commission instead of a fixed salary.

Example

June Sales

UGX 2,200,000

Commission Rate

60%

Gross Salary

UGX 1,320,000

The commission percentage shall be configurable and not hardcoded.

Example

Default = 60%

Later it may become

50%

55%

65%

etc.

The system administrator shall be able to change it.

Payroll Cycle

Payroll shall normally be processed once every month.

Example

June Payroll

July Payroll

August Payroll

Each payroll cycle must be stored permanently.

Payroll history must never be overwritten.

Payroll Calculation

When processing payroll, the system shall automatically calculate

Total Sales during payroll period

×

Commission Rate

=

Gross Salary

Example

Sales

UGX 2,200,000

Commission

60%

Gross Salary

UGX 1,320,000

Dynamic Payroll Deductions

During payroll processing the administrator may apply deductions.

These deductions shall not be fixed.

The admin can enter any amount.

Examples

Tax

50,000

Penalty

20,000

Loan Recovery

100,000

Cash Advance Recovery

70,000

Uniform

40,000

Other

Any custom amount

Each deduction must contain

Deduction Name

Amount

Reason

Entered By

Date

Notes

The total deductions shall be calculated automatically.

Net Salary

Net Salary

=

Gross Salary

−

Total Deductions

Example

Gross Salary

1,320,000

Tax

50,000

Penalty

20,000

Advance Recovery

100,000

Net Salary

1,150,000

Payroll History

Every payroll generated shall remain permanently available.

Admin must be able to open any month and view

Payroll Month

Gross Salary

Commission %

Sales

Deductions

Net Salary

Payment Status

Payment Date

Paid By

Notes

Nothing should disappear after payment.

Deduction History

The system shall maintain a complete deduction history.

Example

John

June

Tax

50,000

July

Tax

40,000

August

No Tax

September

Tax

60,000

Admin should easily answer

How much tax has John paid this year?

How much has been deducted overall?

Which months had deductions?

Salary Advances

Employees may request money before payroll.

Example

10 June

Advance

UGX 200,000

25 June

Advance

UGX 150,000

Total Advances

350,000

At payroll time

Gross Salary

1,320,000

Advance Recovery

350,000

Net Pay

970,000

All advances must remain recorded.

Advance Management

Each advance shall contain

Employee

Amount

Reason

Request Date

Approved By

Approval Date

Status

Recovered?

Recovery Payroll Month

Remaining Balance

An advance should never disappear after recovery.

Partial Advance Recovery

The system should allow partial recovery.

Example

Advance

500,000

June Recovery

200,000

Remaining

300,000

July Recovery

300,000

Balance

0

Multiple Deductions

One payroll can contain many deductions.

Example

Tax

50,000

Advance

100,000

Penalty

20,000

Loan

70,000

Uniform

40,000

The payroll shall automatically total them.

Payroll Approval Workflow

Recommended

Draft Payroll

↓

Review Payroll

↓

Approve Payroll

↓

Pay Payroll

↓

Locked

Approved payroll should not be editable.

If changes are required

Admin must reverse payroll

or

Create payroll adjustment

Payroll Adjustments

Sometimes payroll mistakes happen.

Instead of editing old payroll

The system should create an adjustment record.

Example

Adjustment

+20,000

Reason

Incorrect Tax

Date

Recorded By

This preserves audit history.

Payroll Audit Trail

Every payroll action shall be logged.

Who generated payroll

Who edited deductions

Who approved payroll

Who made payment

Who reversed payment

Date and Time

Old Value

New Value

Payroll Status

Possible statuses

Draft

Pending Approval

Approved

Paid

Cancelled

Reversed

Expense Integration

Once payroll is marked Paid

An expense record shall automatically be created.

Expense Type

Payroll

Employee

Payroll Month

Gross Salary

Deductions

Net Salary

Payment Date

Reference Number

Payment Method

This ensures payroll appears in business expenses automatically.

Payment Methods

Support

Cash

Mobile Money

Bank Transfer

Cheque

Other

Store transaction reference.

Payroll Reports

Admin should generate reports.

Examples

Monthly Payroll Summary

Payroll by Employee

Payroll by Month

Total Commission Paid

Total Taxes Deducted

Total Advances

Outstanding Advances

Payroll Expenses

Commission Report

Top Earners

Lowest Earners

Yearly Payroll

Employee Payroll Profile

Each employee should have a payroll dashboard showing

Payroll Type

Commission %

Current Month Sales

Gross Salary

Net Salary

Payroll History

Deduction History

Advance History

Outstanding Advances

Total Earnings

Total Deductions

Total Commission Paid

Search & Filters

Search by

Employee

Month

Year

Payroll Status

Payment Status

Commission Employee

Salary Employee

Permissions

Administrator

Generate Payroll

Approve Payroll

Pay Payroll

Reverse Payroll

Edit Deductions

Create Advances

View Reports

Manager

View Payroll

Create Advances

Cannot approve payroll

Staff

View only their own payroll history

View deductions

View advances

Cannot edit anything

Notifications (Optional)

Notify employee when

Payroll generated

Payroll approved

Payroll paid

Advance approved

Advance recovered

Deduction applied

Dashboard Widgets

Payroll Due

Payroll Processed

Payroll Expense This Month

Outstanding Advances

Employees Paid

Employees Pending

Total Commission

Total Deductions

Important Database Records

The system should maintain separate records for:

Payroll Runs (one record per employee per payroll period)
Payroll Deductions (multiple records linked to a payroll run)
Salary Advances (including balances and recovery history)
Advance Recovery Transactions (to support partial repayments)
Commission Calculations (snapshot of sales, commission rate, and gross pay at the time of payroll)
Payroll Payments (payment method, reference, status, and date)
Payroll Adjustments (post-processing corrections without editing historical payroll)
Payroll Audit Logs (who performed every payroll-related action)
Expense Records (automatically created after payroll is paid)
Example End-to-End Scenario
During June, Alice generates UGX 2,200,000 in salon services.
Her commission rate is 60%, giving a gross salary of UGX 1,320,000.
On June 15, she receives a salary advance of UGX 200,000.
At month end, the admin processes June payroll.
The admin applies:
Tax: UGX 50,000
Advance recovery: UGX 200,000
The system calculates:
Gross Salary: UGX 1,320,000
Total Deductions: UGX 250,000
Net Salary: UGX 1,070,000
The payroll is approved and marked as Paid.
An expense entry for UGX 1,070,000 is automatically created in the expenses module.
Six months later, the admin can still view:
June sales
Commission rate used
Gross salary
Every deduction applied
Advance recovered
Payment date and method
Expense record
Audit trail showing who processed and approved the payroll



access the database schema in the project root directory in u547313549_nailville.sql

to access the database use the following credentials
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u547313549_nailville
DB_USERNAME=root
DB_PASSWORD=

all database schema adjustments to be made must be logged to a file new_schema.sql in the root project