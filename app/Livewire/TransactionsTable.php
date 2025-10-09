<?php

namespace App\Livewire;

use App\Models\Transaction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BooleanColumn;
use Livewire\Component;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class TransactionsTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(Transaction::query())
            ->columns([
                TextColumn::make('transaction_type')
                    ->label('Transaction Type')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Income' => 'success',
                        'Expense' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('customer_name')
                    ->label('Customer Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                // Add filters here if needed
            ])
            ->actions([
                // Add actions here if needed
            ])
            ->bulkActions([
                // Add bulk actions here if needed
            ])
            ->headerActions([
                // Add header actions here if needed
            ])
            ->emptyStateHeading('No transactions found')
            ->emptyStateDescription('Create your first transaction to get started.')
            ->striped()
            ->deferLoading();
    }

    public function render()
    {
        return view('livewire.transactions-table');
    }
}
