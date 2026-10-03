<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobPostingResource\Pages;
use App\Models\JobPosting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JobPostingResource extends Resource
{
    protected static ?string $model = JobPosting::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Bursa Kerja (BKK)';
    protected static ?string $modelLabel = 'Lowongan Kerja';
    protected static ?string $pluralModelLabel = 'Informasi Lowongan Kerja';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('judul')
                    ->label('Judul Lowongan')
                    ->required(),

                Forms\Components\TextInput::make('perusahaan')
                    ->label('Nama Perusahaan / Instansi')
                    ->required(),

                Forms\Components\TextInput::make('posisi')
                    ->label('Posisi / Jabatan')
                    ->required(),

                Forms\Components\TextInput::make('lokasi')
                    ->label('Lokasi Kerja'),

                Forms\Components\Textarea::make('persyaratan')
                    ->label('Persyaratan & Kualifikasi')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('link_pendaftaran')
                    ->label('Link Pendaftaran / Form')
                    ->url(),

                Forms\Components\TextInput::make('kontak')
                    ->label('Kontak HRD / CP'),

                Forms\Components\DatePicker::make('deadline')
                    ->label('Batas Akhir Pendaftaran'),

                Forms\Components\Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('perusahaan')
                    ->label('Perusahaan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('posisi')
                    ->label('Posisi')
                    ->searchable(),

                Tables\Columns\TextColumn::make('lokasi')
                    ->label('Lokasi'),

                Tables\Columns\TextColumn::make('deadline')
                    ->label('Deadline')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Post')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
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
            'index' => Pages\ListJobPostings::route('/'),
            'create' => Pages\CreateJobPosting::route('/create'),
            'edit' => Pages\EditJobPosting::route('/{record}/edit'),
        ];
    }
}
