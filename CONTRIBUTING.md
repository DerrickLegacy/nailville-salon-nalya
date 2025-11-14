# Contributing to Nailville Salon Management System

Thank you for considering contributing to this project! We welcome contributions from everyone.

## Code of Conduct

By participating in this project, you agree to maintain a respectful and inclusive environment for all contributors.

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check existing issues to avoid duplicates. When creating a bug report, include:

- **Clear title and description**
- **Steps to reproduce** the issue
- **Expected behavior** vs **actual behavior**
- **Screenshots** if applicable
- **Environment details** (OS, PHP version, Laravel version, browser)

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion, include:

- **Clear title and description**
- **Use case** explaining why this enhancement would be useful
- **Possible implementation** if you have ideas

### Pull Requests

1. **Fork the repository** and create your branch from `main`
2. **Follow coding standards** (PSR-12 for PHP)
3. **Write tests** for new features
4. **Update documentation** as needed
5. **Ensure tests pass** before submitting
6. **Write clear commit messages**

## Development Setup

### Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL/PostgreSQL

### Setup Steps

```bash
# Clone your fork
git clone https://github.com/your-username/nailville-salon.git
cd nailville-salon

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Build assets
npm run dev
```

## Coding Standards

### PHP

- Follow **PSR-12** coding standard
- Use **type hints** for parameters and return types
- Write **PHPDoc** comments for classes and methods
- Keep methods **small and focused**

Example:

```php
/**
 * Create a new transaction.
 *
 * @param  array  $data
 * @return \App\Models\Transaction
 */
public function createTransaction(array $data): Transaction
{
    return Transaction::create($data);
}
```

### JavaScript

- Use **ES6+** syntax
- Follow **Airbnb JavaScript Style Guide**
- Use **meaningful variable names**
- Add comments for complex logic

### Blade Templates

- Use **proper indentation** (4 spaces)
- Keep templates **clean and readable**
- Extract reusable parts into **components**
- Use **@props** for component properties

### CSS/Tailwind

- Use **Tailwind utility classes** first
- Create custom CSS only when necessary
- Follow **mobile-first** approach
- Use **semantic class names** for custom CSS

## Testing

### Writing Tests

- Write tests for **all new features**
- Aim for **high code coverage**
- Use **descriptive test names**
- Follow **AAA pattern** (Arrange, Act, Assert)

Example:

```php
/** @test */
public function user_can_create_income_transaction()
{
    // Arrange
    $user = User::factory()->create();
    $data = ['amount' => 50000, ...];
    
    // Act
    $response = $this->actingAs($user)
        ->post(route('transactions.store'), $data);
    
    // Assert
    $response->assertRedirect();
    $this->assertDatabaseHas('transactions', ['amount' => 50000]);
}
```

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/TransactionTest.php

# Run with coverage
php artisan test --coverage

# Run specific test method
php artisan test --filter=user_can_create_income_transaction
```

## Git Workflow

### Branch Naming

- `feature/description` - New features
- `bugfix/description` - Bug fixes
- `hotfix/description` - Urgent fixes
- `refactor/description` - Code refactoring
- `docs/description` - Documentation updates

### Commit Messages

Follow the **Conventional Commits** specification:

```
type(scope): subject

body (optional)

footer (optional)
```

Types:
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting)
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

Examples:

```
feat(transactions): add date filter to transaction list

fix(transactions): correct date field in edit form

docs(readme): update installation instructions

test(transactions): add tests for transaction CRUD operations
```

### Pull Request Process

1. **Update your fork** with the latest changes from main
2. **Create a feature branch** from main
3. **Make your changes** with clear commits
4. **Run tests** and ensure they pass
5. **Push to your fork** and create a pull request
6. **Fill out the PR template** completely
7. **Wait for review** and address feedback

### Pull Request Template

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
- [ ] Tests pass locally
- [ ] New tests added
- [ ] Manual testing completed

## Screenshots (if applicable)

## Checklist
- [ ] Code follows project style guidelines
- [ ] Self-review completed
- [ ] Comments added for complex code
- [ ] Documentation updated
- [ ] No new warnings generated
```

## Database Migrations

### Creating Migrations

```bash
php artisan make:migration create_table_name
```

### Migration Guidelines

- Use **descriptive names**
- Include **up and down methods**
- Add **indexes** for foreign keys
- Use **appropriate column types**
- Add **comments** for complex migrations

Example:

```php
public function up(): void
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->string('transaction_id')->unique();
        $table->unsignedBigInteger('employee_id');
        $table->decimal('amount', 10, 2);
        $table->date('date');
        $table->timestamps();
        
        $table->foreign('employee_id')
            ->references('employee_id')
            ->on('employees')
            ->onDelete('cascade');
            
        $table->index('date');
        $table->index('transaction_type');
    });
}
```

## UI/UX Guidelines

### Responsive Design

- Test on **mobile, tablet, and desktop**
- Use **Tailwind breakpoints** (sm, md, lg, xl, 2xl)
- Ensure **touch-friendly** interfaces
- Test with **different screen sizes**

### Accessibility

- Use **semantic HTML**
- Add **ARIA labels** where needed
- Ensure **keyboard navigation** works
- Maintain **color contrast** ratios
- Test with **screen readers**

### Performance

- **Optimize images** before committing
- Use **lazy loading** for images
- Minimize **JavaScript bundle size**
- Use **server-side pagination** for large datasets

## Documentation

### Code Documentation

- Add **PHPDoc comments** to all public methods
- Document **complex algorithms**
- Explain **"why"** not just "what"
- Keep documentation **up to date**

### README Updates

When adding features, update:
- Feature list
- Installation steps (if changed)
- Configuration options
- Usage examples

## Questions?

If you have questions, feel free to:
- Open an issue for discussion
- Contact the maintainers
- Check existing documentation

## Recognition

Contributors will be recognized in:
- README.md contributors section
- Release notes
- Project documentation

Thank you for contributing! 🎉
