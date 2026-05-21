<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Cita — VetCare</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #eff6ff;
            color: #1f2937;
            padding: 32px 16px;
        }
        .wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(99,102,241,0.12);
        }
        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 40px 32px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .header .icon {
            font-size: 48px;
            margin-bottom: 12px;
        }
        .header p {
            color: #c7d2fe;
            font-size: 14px;
            margin-top: 6px;
        }
        .body { padding: 36px 32px; }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            color: #312e81;
            margin-bottom: 12px;
        }
        .intro {
            font-size: 14px;
            color: #374151;
            line-height: 1.7;
            margin-bottom: 28px;
        }
        .countdown-box {
            background: linear-gradient(135deg, #ede9fe, #dbeafe);
            border: 1px solid #c4b5fd;
            border-radius: 12px;
            padding: 20px 24px;
            text-align: center;
            margin-bottom: 24px;
        }
        .countdown-box .hours {
            font-size: 40px;
            font-weight: 900;
            color: #4f46e5;
            line-height: 1;
        }
        .countdown-box .hours-label {
            font-size: 12px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
        }
        .card {
            background: #f5f3ff;
            border: 1px solid #ddd6fe;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 28px;
        }
        .card-title {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6b7280;
            margin-bottom: 16px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #ede9fe;
            font-size: 14px;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #6b7280; font-weight: 500; }
        .detail-value { color: #111827; font-weight: 700; }
        .tips-box {
            background: #f0fdf4;
            border: 1px solid #d1fae5;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 28px;
        }
        .tips-box .tip-title {
            font-size: 12px;
            font-weight: 800;
            color: #065f46;
            margin-bottom: 8px;
        }
        .tips-box ul {
            padding-left: 18px;
            font-size: 13px;
            color: #374151;
            line-height: 1.8;
        }
        .footer-text {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.7;
            margin-bottom: 8px;
        }
        .footer {
            background: #f9fafb;
            padding: 20px 32px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #9ca3af;
        }
        .footer strong { color: #4f46e5; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="icon">🔔</div>
            <h1>VetCare</h1>
            <p>Recordatorio de Cita Veterinaria</p>
        </div>

        <div class="body">
            <p class="greeting">
                ¡Hola, {{ $appointment->pet->owner->name ?? 'Estimado propietario' }}!
            </p>
            <p class="intro">
                Le recordamos que tiene una <strong>cita veterinaria programada para mañana</strong>
                con <strong>{{ $appointment->pet->name }}</strong>. Asegúrese de estar preparado.
            </p>

            <div class="countdown-box">
                <div class="hours">24</div>
                <div class="hours-label">horas para su cita</div>
            </div>

            <div class="card">
                <div class="card-title">📋 Detalles de la Cita</div>
                <div class="detail-row">
                    <span class="detail-label">Mascota</span>
                    <span class="detail-value">{{ $appointment->pet->name }} ({{ ucfirst($appointment->pet->species) }})</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Veterinario</span>
                    <span class="detail-value">{{ $appointment->veterinarian->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Fecha</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('d/m/Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Hora</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('H:i') }} hrs</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Motivo</span>
                    <span class="detail-value">
                        {{ \App\Models\Appointment::$reasons[$appointment->reason] ?? $appointment->reason }}
                    </span>
                </div>
            </div>

            <div class="tips-box">
                <div class="tip-title">💡 Recomendaciones para la cita</div>
                <ul>
                    <li>Lleve el historial médico y cartilla de vacunación de su mascota.</li>
                    <li>Si su mascota requiere ayuno, asegúrese de seguir las instrucciones previas.</li>
                    <li>Arrive 10 minutos antes de su hora programada.</li>
                    <li>En caso de no poder asistir, cancele su cita con anticipación.</li>
                </ul>
            </div>

            <p class="footer-text">
                Si necesita cancelar o reprogramar su cita, por favor contáctenos a la brevedad posible.
                ¡Gracias por confiar en <strong style="color:#4f46e5">VetCare</strong>! 🐾
            </p>
        </div>

        <div class="footer">
            <strong>VetCare</strong> — Sistema de Gestión Veterinaria<br>
            Este es un correo automático generado por el sistema de recordatorios.
        </div>
    </div>
</body>
</html>
