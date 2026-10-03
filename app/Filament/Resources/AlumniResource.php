<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlumniResource\Pages;
use App\Models\Alumni;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AlumniResource extends Resource
{
    protected static ?string $model = Alumni::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Data Utama';
    protected static ?string $modelLabel = 'Alumni';
    protected static ?string $pluralModelLabel = 'Data Alumni';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nisn')
                    ->label('NISN')
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('nama')
                    ->label('Nama Lengkap')
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->required(),

                Forms\Components\Select::make('jurusan')
                    ->label('Jurusan / Program Keahlian')
                    ->options([
                        'Teknik Komputer dan Jaringan' => 'Teknik Komputer dan Jaringan (TKJ)',
                        'Rekayasa Perangkat Lunak' => 'Rekayasa Perangkat Lunak (RPL)',
                        'Multimedia' => 'Multimedia (MM)',
                        'Teknik Kendaraan Ringan' => 'Teknik Kendaraan Ringan (TKR)',
                        'Akuntansi dan Keuangan' => 'Akuntansi dan Keuangan (AKL)',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('tahun_lulus')
                    ->label('Tahun Lulus / Angkatan')
                    ->required()
                    ->numeric()
                    ->default(date('Y')),

                Forms\Components\TextInput::make('no_hp')
                    ->label('No. WhatsApp / HP')
                    ->tel(),

                Forms\Components\TextInput::make('email')
                    ->label('Alamat Email')
                    ->email(),

                Forms\Components\Textarea::make('alamat')
                    ->label('Alamat Rumah')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Alumni')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->label('Tgl Lahir')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jurusan')
                    ->label('Jurusan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tahun_lulus')
                    ->label('Angkatan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('no_hp')
                    ->label('No WA')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jurusan')
                    ->options([
                        'Teknik Komputer dan Jaringan' => 'TKJ',
                        'Rekayasa Perangkat Lunak' => 'RPL',
                        'Multimedia' => 'Multimedia',
                        'Teknik Kendaraan Ringan' => 'TKR',
                        'Akuntansi dan Keuangan' => 'Akuntansi',
                    ]),
                Tables\Filters\SelectFilter::make('tahun_lulus')
                    ->options([
                        2023 => '2023',
                        2024 => '2024',
                        2025 => '2025',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAlumnis::route('/'),
            'create' => Pages\CreateAlumni::route('/create'),
            'edit' => Pages\EditAlumni::route('/{record}/edit'),
        ];
    }
}
