<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Cita — VetCare</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f0fdf4;
            color: #1f2937;
            padding: 32px 16px;
        }
        .wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(16,185,129,0.12);
        }
        .header {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
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
            color: #a7f3d0;
            font-size: 14px;
            margin-top: 6px;
        }
        .body {
            padding: 36px 32px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            color: #065f46;
            margin-bottom: 12px;
        }
        .intro {
            font-size: 14px;
            color: #374151;
            line-height: 1.7;
            margin-bottom: 28px;
        }
        .card {
            background: #f0fdf4;
            border: 1px solid #d1fae5;
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
            border-bottom: 1px solid #d1fae5;
            font-size: 14px;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #6b7280; font-weight: 500; }
        .detail-value { color: #111827; font-weight: 700; }
        .status-badge {
            display: inline-block;
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 12px;
            font-weight: 700;
        }
        .note-box {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 10px;
            padding: 16px;
            font-size: 13px;
            color: #92400e;
            line-height: 1.6;
            margin-bottom: 28px;
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
        .footer strong { color: #059669; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="icon">🐾</div>
            <h1>VetCare</h1>
            <p>Confirmación de Cita Veterinaria</p>
        </div>

        <div class="body">
            <p class="greeting">
                ✅ ¡Cita confirmada, {{ $appointment->pet->owner->name ?? 'Estimado propietario' }}!
            </p>
            <p class="intro">
                Nos complace informarle que su cita veterinaria para <strong>{{ $appointment->pet->name }}</strong>
                ha sido registrada exitosamente en nuestro sistema. A continuación encontrará los detalles de su cita.
            </p>

            <div class="card">
                <div class="card-title">📋 Detalles de la Cita</div>
                <div class="detail-row">
                    <span class="detail-label">Mascota</span>
                    <span class="detail-value">{{ $appointment->pet->name }} ({{ ucfirst($appointment->pet->species) }})</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Raza</span>
                    <span class="detail-value">{{ $appointment->pet->breed }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Veterinario</span>
                    <span class="detail-value">{{ $appointment->veterinarian->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Fecha y Hora</span>
                    <span class="detail-value">
                        {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('d/m/Y') }}
                        a las {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('H:i') }} hrs
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Motivo</span>
                    <span class="detail-value">
                        {{ \App\Models\Appointment::$reasons[$appointment->reason] ?? $appointment->reason }}
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Estado</span>
                    <span class="status-badge">
                        {{ \App\Models\Appointment::$statuses[$appointment->status] ?? $appointment->status }}
                    </span>
                </div>
            </div>

            @if($appointment->notes)
            <div class="note-box">
                <strong>📝 Notas adicionales:</strong><br>
                {{ $appointment->notes }}
            </div>
            @endif

            <p class="footer-text">
                Le recordamos que le enviaremos un recordatorio automático 24 horas antes de su cita.
                Si necesita cancelar o modificar su cita, por favor contáctenos con antelación suficiente.
            </p>
            <p class="footer-text">
                ¡Gracias por confiar en <strong style="color:#059669">VetCare</strong> para el cuidado de su mascota! 🐾
            </p>
        </div>

        <div class="footer">
            <strong>VetCare</strong> — Sistema de Gestión Veterinaria<br>
            Este es un correo automático, por favor no responda a esta dirección.
        </div>
    </div>
</body>
</html>
