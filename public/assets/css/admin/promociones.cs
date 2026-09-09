/* =========================================
   PROMOCIONES - ADMIN
========================================= */

.promociones-page {
    width: 100%;
}


/* =========================================
   ENCABEZADO
========================================= */

.promociones-page .page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;

    margin-bottom: 30px;
}

.promociones-page .page-header h1 {
    margin: 0 0 6px;

    color: var(--dark);

    font-size: 28px;
    font-weight: 700;
}

.promociones-page .page-header p {
    margin: 0;

    color: var(--gray);

    font-size: 14px;
}


/* =========================================
   TARJETA PRINCIPAL
========================================= */

.promociones-table-card {
    background: #ffffff;

    border-radius: var(--radius-lg);

    padding: 25px;

    box-shadow: var(--shadow);

    border: 1px solid #E5E7EB;
}


/* =========================================
   TABLA
========================================= */

#tablaPromociones {
    width: 100% !important;

    border-collapse: collapse;
}

#tablaPromociones thead th {
    background: var(--primary);

    color: #ffffff;

    padding: 15px;

    text-align: left;

    font-size: 13px;

    font-weight: 600;

    white-space: nowrap;
}

#tablaPromociones tbody td {
    padding: 16px 15px;

    border-bottom: 1px solid #EEF0F3;

    color: #374151;

    font-size: 14px;

    vertical-align: middle;
}

#tablaPromociones tbody tr {
    transition:
        background-color .2s ease;
}

#tablaPromociones tbody tr:hover {
    background: #F8FAFC;
}


/* =========================================
   IDENTIFICACIÓN
========================================= */

.promocion-id {
    color: #9CA3AF;

    font-size: 13px;

    font-weight: 500;
}

.promocion-info {
    display: flex;

    flex-direction: column;

    gap: 4px;
}

.promocion-info strong {
    color: var(--dark);

    font-size: 14px;

    font-weight: 600;
}

.promocion-info span {
    color: var(--gray);

    font-size: 12px;

    line-height: 1.4;
}


/* =========================================
   DESCUENTO
========================================= */

.promocion-descuento {
    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-width: 72px;

    padding: 7px 12px;

    border-radius: 50px;

    background: #DBEAFE;

    color: #1D4ED8;

    font-size: 13px;

    font-weight: 700;
}


/* =========================================
   FECHAS
========================================= */

.promocion-fecha {
    display: flex;

    align-items: center;

    gap: 8px;

    color: #4B5563;

    white-space: nowrap;
}

.promocion-fecha i {
    color: #9CA3AF;

    font-size: 13px;
}


/* =========================================
   ESTADOS
========================================= */

.promocion-estado {
    display: inline-flex;

    align-items: center;

    gap: 7px;

    padding: 7px 12px;

    border-radius: 50px;

    font-size: 12px;

    font-weight: 600;

    white-space: nowrap;
}

.promocion-estado::before {
    content: "";

    width: 7px;

    height: 7px;

    border-radius: 50%;

    background: currentColor;
}

.promocion-estado.activa {
    background: #DCFCE7;

    color: #166534;
}

.promocion-estado.inactiva {
    background: #F3F4F6;

    color: #6B7280;
}


/* =========================================
   ACCIONES
========================================= */

.promocion-actions {
    display: flex;

    align-items: center;

    gap: 7px;
}

.promocion-actions .btn-action {
    width: 36px;

    height: 36px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    border-radius: 9px;

    text-decoration: none;

    transition:
        transform .2s ease,
        background-color .2s ease;
}

.promocion-actions .btn-action:hover {
    transform: translateY(-2px);
}

.promocion-actions .btn-edit {
    background: #EFF6FF;

    color: #2563EB;
}

.promocion-actions .btn-edit:hover {
    background: #2563EB;

    color: #ffffff;
}

.promocion-actions .btn-delete {
    background: #FEF2F2;

    color: #DC2626;
}

.promocion-actions .btn-delete:hover {
    background: #EF4444;

    color: #ffffff;
}


/* =========================================
   TABLA VACÍA
========================================= */

.promociones-empty {
    padding: 65px 30px !important;

    text-align: center;
}

.promociones-empty-content {
    display: flex;

    flex-direction: column;

    align-items: center;

    gap: 10px;
}

.promociones-empty-content i {
    display: flex;

    align-items: center;

    justify-content: center;

    width: 65px;

    height: 65px;

    margin-bottom: 5px;

    border-radius: 18px;

    background: #EFF6FF;

    color: #2563EB;

    font-size: 25px;
}

.promociones-empty-content strong {
    color: var(--dark);

    font-size: 16px;
}

.promociones-empty-content span {
    color: var(--gray);

    font-size: 13px;
}


/* =========================================
   RESPONSIVE
========================================= */

@media (max-width: 768px) {

    .promociones-page .page-header {
        flex-direction: column;

        align-items: flex-start;
    }

    .promociones-page .page-header .btn-primary {
        width: 100%;
    }

    .promociones-table-card {
        padding: 15px;
    }

    #tablaPromociones {
        min-width: 850px;
    }

}