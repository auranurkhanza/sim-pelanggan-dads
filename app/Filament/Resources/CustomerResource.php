<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Aktivasi Pelanggan';

    protected static ?string $pluralModelLabel = 'Input Aktivasi Pelanggan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('customer_code')
                            ->label('Kode Pelanggan')
                            ->placeholder('Contoh: CUST-001')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Pelanggan (Sesuai KTP)')
                            ->required(),
                        Forms\Components\TextInput::make('phone_number')
                            ->label('Nomor Telepon / WA')
                            ->tel()
                            ->required(),
                        Forms\Components\TextInput::make('region')
                            ->label('Wilayah / Area')
                            ->required(),
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat Lengkap Pemasangan')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\FileUpload::make('ktp_path')
                            ->label('Upload Foto KTP')
                            ->image()
                            ->directory('documents/ktp')
                            ->imagePreviewHeight('150')
                            ->maxSize(3072)
                            ->required(),
                        Forms\Components\FileUpload::make('bast_path')
                            ->label('Upload Foto BAST (Berita Acara)')
                            ->image()
                            ->directory('documents/bast')
                            ->imagePreviewHeight('150')
                            ->maxSize(3072)
                            ->required(),
                        Forms\Components\FileUpload::make('customer_photo_path')
                            ->label('Upload Foto Rumah / Pelanggan')
                            ->image()
                            ->directory('documents/customers')
                            ->imagePreviewHeight('150')
                            ->maxSize(3072)
                            ->required(),
                    ])->columns(3),

                // Bagian ini hanya menampilkan hasil validasi otomatis sistem
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status Validasi Sistem')
                            ->options([
                                'belum_diperiksa' => 'Belum Diperiksa',
                                'belum_lengkap' => 'Belum Lengkap',
                                'perlu_diperbaiki' => 'Perlu Diperbaiki',
                                'valid' => 'Valid',
                                'tidak_valid' => 'Tidak Valid',
                            ])
                            ->disabled(), // Dikunci agar tidak bisa diubah manual oleh tim lapangan

                        Forms\Components\Textarea::make('validation_notes')
                            ->label('Catatan Validasi Sistem')
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->hidden(fn ($livewire) => $livewire instanceof Pages\CreateCustomer), // Sembunyikan saat buat data baru
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('region')
                    ->label('Wilayah')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status Validasi')
                    ->enum([
                        'belum_diperiksa' => 'Belum Diperiksa',
                        'belum_lengkap' => 'Belum Lengkap',
                        'perlu_diperbaiki' => 'Perlu Diperbaiki',
                        'valid' => 'Valid',
                        'tidak_valid' => 'Tidak Valid',
                    ])
                    ->colors([
                        'secondary' => 'belum_diperiksa',
                        'warning' => 'belum_lengkap',
                        'danger' => 'perlu_diperbaiki',
                        'success' => 'valid',
                        'danger' => 'tidak_valid',
                    ]),
                Tables\Columns\TextColumn::make('validation_notes')
                    ->label('Catatan Sistem')
                    ->wrap()
                    ->limit(40),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Input')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'belum_diperiksa' => 'Belum Diperiksa',
                        'belum_lengkap' => 'Belum Lengkap',
                        'perlu_diperbaiki' => 'Perlu Diperbaiki',
                        'valid' => 'Valid',
                        'tidak_valid' => 'Tidak Valid',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Lihat / Edit'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}