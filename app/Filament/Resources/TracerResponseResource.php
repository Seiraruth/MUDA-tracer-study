<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TracerResponseResource\Pages;
use App\Models\TracerResponse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TracerResponseResource extends Resource
{
    protected static ?string $model = TracerResponse::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationGroup = 'Tracer Study';
    protected static ?string $modelLabel = 'Hasil Kuesioner';
    protected static ?string $pluralModelLabel = 'Hasil Tracer Study';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('alumni_id')
                    ->relationship('alumni', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('tahun_tracer')
                    ->required()
                    ->numeric()
                    ->default(date('Y')),

                Forms\Components\Select::make('status_utama')
                    ->options([
                        'Bekerja' => 'Bekerja',
                        'Kuliah' => 'Melanjutkan (Kuliah)',
                        'Wirausaha' => 'Wirausaha',
                        'Mencari Kerja' => 'Mencari Kerja',
                    ])
                    ->required(),

                Forms\Components\Section::make('Detail Pekerjaan (Jika Bekerja)')
                    ->schema([
                        Forms\Components\TextInput::make('nama_perusahaan'),
                        Forms\Components\TextInput::make('jabatan'),
                        Forms\Components\TextInput::make('kisaran_gaji'),
                        Forms\Components\Toggle::make('linear_dengan_jurusan')
                            ->label('Sesuai Jurusan SMK?'),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Kuliah (Jika Melanjutkan)')
                    ->schema([
                        Forms\Components\TextInput::make('nama_kampus'),
                        Forms\Components\TextInput::make('program_studi'),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Wirausaha (Jika Berwirausaha)')
                    ->schema([
                        Forms\Components\TextInput::make('nama_usaha'),
                        Forms\Components\TextInput::make('bidang_usaha'),
                        Forms\Components\TextInput::make('omset_bulanan'),
                    ])->columns(3),

                Forms\Components\Textarea::make('saran_sekolah')
                    ->label('Saran & Masukan untuk Sekolah')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('alumni.nama')
                    ->label('Nama Alumni')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('alumni.nisn')
                    ->label('NISN')
                    ->searchable(),

                Tables\Columns\TextColumn::make('alumni.jurusan')
                    ->label('Jurusan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tahun_tracer')
                    ->label('Tahun Tracer')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status_utama')
                    ->label('Status')
                    ->colors([
                        'success' => 'Bekerja',
                        'info' => 'Kuliah',
                        'warning' => 'Wirausaha',
                        'danger' => 'Mencari Kerja',
                    ]),

                Tables\Columns\IconColumn::make('linear_dengan_jurusan')
                    ->label('Linear')
                    ->boolean(),

                Tables\Columns\TextColumn::make('nama_perusahaan')
                    ->label('Perusahaan / Kampus / Usaha')
                    ->getStateUsing(fn (TracerResponse $record): ?string => match ($record->status_utama) {
                        'Bekerja' => $record->nama_perusahaan,
                        'Kuliah' => $record->nama_kampus,
                        'Wirausaha' => $record->nama_usaha,
                        default => '-'
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Isi')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_utama')
                    ->options([
                        'Bekerja' => 'Bekerja',
                        'Kuliah' => 'Kuliah',
                        'Wirausaha' => 'Wirausaha',
                        'Mencari Kerja' => 'Mencari Kerja',
                    ]),

                Tables\Filters\SelectFilter::make('tahun_tracer')
                    ->options([
                        2024 => '2024',
                        2025 => '2025',
                        2026 => '2026',
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
            'index' => Pages\ListTracerResponses::route('/'),
            'create' => Pages\CreateTracerResponse::route('/create'),
            'edit' => Pages\EditTracerResponse::route('/{record}/edit'),
        ];
    }
}
