<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Vacunas — VetCare</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #fdf4ff;
            color: #1f2937;
            padding: 32px 16px;
        }
        .wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(168,85,247,0.12);
        }
        .header {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            padding: 40px 32px;
            text-align: center;
        }
        .header h1 { color: #fff; font-size: 26px; font-weight: 800; letter-spacing: -0.5px; }
        .header .icon { font-size: 48px; margin-bottom: 12px; }
        .header p { color: #e9d5ff; font-size: 14px; margin-top: 6px; }
        .body { padding: 36px 32px; }

        .greeting { font-size: 18px; font-weight: 700; color: #6d28d9; margin-bottom: 12px; }
        .intro { font-size: 14px; color: #374151; line-height: 1.7; margin-bottom: 28px; }

        /* Pet profile card */
        .pet-card {
            display: flex;
            align-items: center;
            gap: 16px;
            background: #f5f3ff;
            border: 1px solid #ddd6fe;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
        }
        .pet-avatar {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; flex-shrink: 0;
        }
        .pet-info .pet-name { font-size: 18px; font-weight: 800; color: #111827; }
        .pet-info .pet-meta { font-size: 12px; color: #6b7280; margin-top: 2px; }

        /* Section headers */
        .section-title {
            font-size: 12px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 1px;
            margin-bottom: 12px; padding-bottom: 8px;
            border-bottom: 2px solid;
        }
        .section-title.overdue { color: #dc2626; border-color: #fca5a5; }
        .section-title.upcoming { color: #d97706; border-color: #fcd34d; }

        /* Vaccine rows */
        .vaccine-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 8px;
            font-size: 13px;
        }
        .vaccine-row.overdue-row { background: #fef2f2; border: 1px solid #fecaca; }
        .vaccine-row.upcoming-row { background: #fffbeb; border: 1px solid #fde68a; }
        .vaccine-name { font-weight: 700; color: #111827; }
        .vaccine-dose { font-size: 11px; color: #6b7280; margin-top: 2px; }
        .vaccine-date-overdue { font-weight: 700; color: #dc2626; font-size: 12px; }
        .vaccine-date-upcoming { font-weight: 700; color: #d97706; font-size: 12px; }
        .days-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
        }
        .days-badge.overdue { background: #fee2e2; color: #b91c1c; }
        .days-badge.upcoming { background: #fef3c7; color: #92400e; }

        .section-block { margin-bottom: 28px; }
        .alert-box {
            background: #fef2f2; border: 1px solid #fecaca;
            border-radius: 12px; padding: 16px; margin-bottom: 24px;
        }
        .alert-box .alert-title { font-size: 13px; font-weight: 800; color: #b91c1c; margin-bottom: 6px; }
        .alert-box p { font-size: 13px; color: #7f1d1d; line-height: 1.6; }

        .cta-box { text-align: center; margin: 24px 0; }
        .cta-btn {
            display: inline-block;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            color: #ffffff; font-weight: 800; font-size: 14px;
            padding: 14px 32px; border-radius: 12px;
            text-decoration: none;
        }

        .footer-text { font-size: 13px; color: #6b7280; line-height: 1.7; margin-bottom: 8px; }
        .footer {
            background: #f9fafb; padding: 20px 32px;
            text-align: center; border-top: 1px solid #e5e7eb;
            font-size: 12px; color: #9ca3af;
        }
        .footer strong { color: #7c3aed; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="icon">💉</div>
            <h1>VetCare</h1>
            <p>Recordatorio del Programa de Vacunación</p>
        </div>

        <div class="body">
            <p class="greeting">
                Estimado/a {{ $pet->owner->name ?? 'Propietario/a' }},
            </p>
            <p class="intro">
                Le escribimos porque <strong>{{ $pet->name }}</strong> tiene vacunas que requieren su atención.
                Mantener el esquema de vacunación al día es fundamental para la salud y protección de su mascota.
            </p>

            <!-- Pet profile -->
            <div class="pet-card">
                <div class="pet-avatar">🐾</div>
                <div class="pet-info">
                    <div class="pet-name">{{ $pet->name }}</div>
                    <div class="pet-meta">
                        {{ ucfirst($pet->species) }} · {{ $pet->breed }}
                        · Peso: {{ $pet->weight }} kg
                    </div>
                </div>
            </div>

            @if($overdueVaccinations->count() > 0)
                <!-- Overdue vaccines -->
                <div class="alert-box">
                    <div class="alert-title">⚠️ ¡Atención! Vacunas Vencidas</div>
                    <p>Las siguientes vacunas ya superaron su fecha de refuerzo. Le recomendamos programar una cita a la brevedad posible.</p>
                </div>

                <div class="section-block">
                    <div class="section-title overdue">🔴 Vacunas Vencidas ({{ $overdueVaccinations->count() }})</div>
                    @foreach($overdueVaccinations as $vaccine)
                        @php
                            $daysPast = \Carbon\Carbon::parse($vaccine->next_dose_due)->diffInDays(now());
                        @endphp
                        <div class="vaccine-row overdue-row">
                            <div>
                                <div class="vaccine-name">{{ $vaccine->name }}</div>
                                <div class="vaccine-dose">Dosis: {{ $vaccine->dose }}</div>
                            </div>
                            <div style="text-align: right;">
                                <div class="vaccine-date-overdue">
                                    Venció: {{ \Carbon\Carbon::parse($vaccine->next_dose_due)->format('d/m/Y') }}
                                </div>
                                <span class="days-badge overdue">hace {{ $daysPast }} día{{ $daysPast !== 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($upcomingVaccinations->count() > 0)
                <!-- Upcoming vaccines -->
                <div class="section-block">
                    <div class="section-title upcoming">🟡 Refuerzos Próximos (en 7 días)</div>
                    @foreach($upcomingVaccinations as $vaccine)
                        @php
                            $daysLeft = now()->diffInDays(\Carbon\Carbon::parse($vaccine->next_dose_due), false);
                        @endphp
                        <div class="vaccine-row upcoming-row">
                            <div>
                                <div class="vaccine-name">{{ $vaccine->name }}</div>
                                <div class="vaccine-dose">Dosis: {{ $vaccine->dose }}</div>
                            </div>
                            <div style="text-align: right;">
                                <div class="vaccine-date-upcoming">
                                    Fecha: {{ \Carbon\Carbon::parse($vaccine->next_dose_due)->format('d/m/Y') }}
                                </div>
                                <span class="days-badge upcoming">en {{ $daysLeft }} día{{ $daysLeft !== 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="cta-box">
                <a href="#" class="cta-btn">
                    📅 Programar Cita de Vacunación
                </a>
            </div>

            <p class="footer-text">
                Si ya atendió las vacunas de su mascota en otro centro veterinario, por favor comparta los registros
                con nuestro personal para mantener el historial actualizado.
            </p>
            <p class="footer-text">
                ¡Gracias por confiar en <strong style="color:#7c3aed">VetCare</strong> para el cuidado de <strong>{{ $pet->name }}</strong>! 🐾
            </p>
        </div>

        <div class="footer">
            <strong>VetCare</strong> — Sistema de Gestión Veterinaria<br>
            Este correo fue generado automáticamente por el sistema de recordatorios de vacunación.
        </div>
    </div>
</body>
</html>
