<?php
declare(strict_types=1);
// Traducciones: español (es), inglés (en) y árabe (ar, escritura de derecha a izquierda).
// Las claves que terminan en _list son listas de párrafos (usar tl()).

$TR = [
'es' => [
 'tag' => 'Hierbas del Oasis · Yerba mate argentina',
 'nav_home' => 'Inicio', 'nav_products' => 'Productos', 'nav_markets' => 'Mercados', 'nav_contact' => 'Contacto',
 'nav_login' => 'Ingresar', 'nav_panel' => 'Mi cuenta', 'nav_logout' => 'Salir', 'nav_admin' => 'Administración',
 'hero_eyebrow' => 'Exportación directa desde Misiones, Argentina',
 'hero_title' => 'La yerba mate argentina auténtica, en tu mesa.',
 'hero_lead' => 'Conectamos a los mejores productores de Misiones con distribuidores y mayoristas de Siria, Líbano, Jordania y Emiratos Árabes. Calidad constante, packaging bilingüe y embarques documentados.',
 'hero_cta1' => 'Pedir cotización', 'hero_cta2' => 'Ver productos',
 'why_title' => '¿Por qué ASHAB?',
 'why1_t' => 'Origen argentino', 'why1_d' => 'Yerba de plantaciones y molinos de Misiones, con trazabilidad por lote.',
 'why2_t' => 'Lista para el mercado árabe', 'why2_d' => 'Etiquetado en árabe, español e inglés, y sabor ajustado al gusto de la región.',
 'why3_t' => 'Embarque documentado', 'why3_d' => 'Certificado de origen, certificados sanitarios, seguro y pago seguro.',
 'why4_t' => 'Relación de largo plazo', 'why4_d' => 'Abastecimiento regular y soporte posventa para distribuidores.',
 'markets_title' => 'Nuestros mercados',
 'markets_lead' => 'Empezamos por los países donde el mate ya es parte de la mesa.',
 'm_sy' => 'Siria', 'm_sy_d' => 'Principal consumidor de yerba mate de Medio Oriente, con una tradición muy arraigada.',
 'm_lb' => 'Líbano', 'm_lb_d' => 'Puerta logística por el puerto de Beirut y consumo fuerte en la montaña y las ciudades.',
 'm_jo' => 'Jordania', 'm_jo_d' => 'Acceso por el puerto de Áqaba y comunidades que ya toman mate.',
 'm_ae' => 'Emiratos Árabes Unidos', 'm_ae_d' => 'Centro de reexportación por Jebel Ali y ferias como Gulfood.',
 'markets_note' => 'Sujeto a las condiciones sanitarias, aduaneras y logísticas vigentes en cada país.',
 'r_other' => 'Otro',

 'products_title' => 'Productos',
 'products_lead' => 'Presentaciones para distribución mayorista. Las empresas aprobadas ven los precios y piden cotización.',
 'price_locked_login' => 'Ingresá para ver el precio', 'price_locked_pending' => 'Disponible cuando aprobemos tu empresa',
 'price_fob' => 'Precio FOB indicativo', 'pack' => 'Presentación', 'req_quote' => 'Solicitar cotización',
 'qty_kg' => 'Cantidad (kg)', 'qty_help' => 'Mínimo 1.000 kg y máximo 48.000 kg por pedido web.', 'dest' => 'País de destino', 'incoterm' => 'Condición',
 'send_req' => 'Enviar solicitud', 'quote_sent' => 'Recibimos tu solicitud. Vas a recibir la proforma en tu cuenta y por WhatsApp.',
 'qty_bad' => 'La cantidad debe estar entre 1.000 y 48.000 kg.', 'too_many' => 'Ya tenés 3 pedidos abiertos. Completalos o cancelalos antes de pedir otro.',
 'wa_ask' => 'Consultar por WhatsApp', 'csrf' => 'La sesión expiró. Volvé a intentar.',

 'contact_title' => 'Contacto', 'contact_lead' => 'Escribinos y te respondemos en 24 horas hábiles.',
 'c_name' => 'Nombre', 'c_company' => 'Empresa', 'c_email' => 'Email', 'c_phone' => 'Teléfono (opcional)', 'c_msg' => 'Mensaje', 'c_send' => 'Enviar',
 'c_thanks' => '¡Gracias! Recibimos tu mensaje.', 'c_bad' => 'Revisá nombre, email y mensaje (mínimo 10 caracteres).',
 'wa_title' => 'Escribinos por WhatsApp', 'wa_intl' => 'Número en formato internacional', 'wa_copy' => 'Copiar número', 'wa_copied' => 'Copiado',
 'wa_qr' => 'Desde una computadora: escaneá el código con tu teléfono.', 'wa_open' => 'Abrir WhatsApp',
 'wa_hours_t' => 'Horario de atención', 'wa_hours' => 'Lunes a viernes, de 9 a 18 h de Argentina. Equivale a 15 a 24 h en Siria, Jordania y Líbano, y a 16 a 01 h en Emiratos.',
 'wa_tip' => 'No hace falta marcar ceros ni prefijos locales: el número ya incluye el código de país (+54) y el 9 de los celulares argentinos.',

 'login_title' => 'Ingresar', 'login_lead' => 'Ingresá con tu cuenta de Google. No guardamos tu contraseña ni te pedimos documentos personales.',
 'login_google' => 'Continuar con Google', 'login_why' => 'Usamos Google para verificar tu email y evitar pedidos falsos. Después te pedimos los datos de tu empresa.',
 'login_unavailable' => 'El ingreso con Google todavía no está configurado en este sitio.',
 'login_err_state' => 'No pudimos verificar el inicio de sesión. Intentá de nuevo.', 'login_err_unverified' => 'Tu cuenta de Google debe tener el email verificado.',
 'login_err_denied' => 'Cancelaste el ingreso con Google.', 'login_blocked' => 'Demasiados intentos. Probá más tarde.', 'need_login' => 'Ingresá para continuar.',
 'logged_out' => 'Cerraste sesión.', 'account_off' => 'Tu cuenta está desactivada. Escribinos por WhatsApp.',

 'perfil_title' => 'Datos de tu empresa', 'perfil_lead' => 'Completalos una sola vez. Los revisamos antes de habilitar precios y pedidos. No pedimos documentos personales.',
 'f_company' => 'Nombre de la empresa', 'f_country' => 'País', 'f_city' => 'Ciudad', 'f_role' => 'Tu cargo en la empresa',
 'reg_SY' => 'N.º de registro comercial (Siria)', 'reg_LB' => 'N.º de registro de comercio (Líbano)', 'reg_JO' => 'N.º de registro de la empresa (Jordania)',
 'reg_AE' => 'N.º de licencia comercial / Trade License (Emiratos)', 'reg_OTRO' => 'N.º de registro mercantil o ID fiscal de la empresa',
 'f_whatsapp' => 'WhatsApp de la empresa (con código de país)', 'f_whatsapp_help' => 'Ejemplo: +971 50 123 4567', 'f_website' => 'Sitio web (opcional)', 'save' => 'Guardar',
 'perfil_saved' => 'Datos guardados. Vamos a revisar tu empresa.',
 'e_company' => 'Ingresá el nombre de la empresa.', 'e_city' => 'Ingresá la ciudad.', 'e_regnum' => 'Ingresá el número de registro de la empresa.',
 'e_whatsapp' => 'Ingresá un WhatsApp válido con código de país, por ejemplo +971501234567.',
 'acc_pendiente' => 'En revisión', 'acc_aprobada' => 'Aprobada', 'acc_rechazada' => 'Rechazada',
 'acc_pending_msg' => 'Estamos revisando los datos de tu empresa (1 a 2 días hábiles). Cuando la aprobemos vas a ver los precios y vas a poder pedir cotizaciones.',
 'acc_rejected_msg' => 'No pudimos aprobar tu cuenta. Escribinos por WhatsApp para aclararlo.', 'acc_notify' => 'Avisar por WhatsApp que me registré',
 'wa_registered' => 'Hola, me registré en ASHAB con la empresa %s (%s). ¿Pueden revisar mi cuenta?',
 'need_approval' => 'Tu empresa todavía no fue aprobada.',

 'panel_title' => 'Mi cuenta', 'p_hello' => 'Hola', 'p_status' => 'Estado de la cuenta', 'p_orders' => 'Mis pedidos', 'p_none' => 'Todavía no hiciste pedidos.',
 'p_date' => 'Fecha', 'p_product' => 'Producto', 'p_qty' => 'Kg', 'p_dest' => 'Destino', 'p_total' => 'Total', 'p_open' => 'Ver', 'p_edit_profile' => 'Editar datos de la empresa',

 'order' => 'Pedido', 'o_total' => 'Total del pedido', 'o_sena' => 'Seña a pagar (50 %)', 'o_saldo' => 'Saldo (50 %)', 'o_price' => 'Precio por kg',
 'o_valid' => 'Proforma válida hasta', 'o_waiting' => 'Estamos preparando tu proforma. Te avisamos por WhatsApp.',
 'o_how' => 'Cómo confirmar tu pedido',
 'o_step1' => '1. Revisá la proforma y los términos.', 'o_step2' => '2. Transferí el 50 % (la seña) a la cuenta indicada.',
 'o_step3' => '3. Informá la transferencia en esta página.', 'o_step4' => '4. Cuando la seña se acredita, confirmamos y empezamos a producir.',
 'o_bank' => 'Datos para transferir', 'o_bank_missing' => 'Te enviamos los datos bancarios junto con la proforma por WhatsApp.',
 'o_pay_t' => 'Informar la transferencia', 'o_pay_bank' => 'Banco de origen', 'o_pay_ref' => 'Referencia o número de operación', 'o_pay_date' => 'Fecha de la transferencia (AAAA-MM-DD)',
 'o_pay_amount' => 'Monto transferido (USD)', 'o_accept' => 'Acepto los términos y la política de seña (la seña no se devuelve si cancelo).',
 'o_pay_btn' => 'Informar pago', 'o_pay_sent' => 'Recibimos tu aviso de pago. Lo verificamos en el banco y te confirmamos.',
 'o_pay_bad' => 'Revisá los datos del pago y aceptá los términos.', 'o_cancel' => 'Cancelar pedido', 'o_cancelled' => 'Pedido cancelado.',
 'o_expired' => 'La proforma venció. Pedí una nueva.', 'o_not_found' => 'No encontramos ese pedido.',
 'st_solicitada' => 'Solicitud recibida', 'st_proforma_emitida' => 'Proforma emitida: falta la seña', 'st_sena_informada' => 'Pago informado: lo estamos verificando',
 'st_sena_acreditada' => 'Seña acreditada: pedido confirmado', 'st_en_produccion' => 'En producción', 'st_embarcada' => 'Embarcada', 'st_cerrada' => 'Cerrada',
 'st_cancelada' => 'Cancelada', 'st_vencida' => 'Proforma vencida',

 'terms_title' => 'Términos de compra y política de seña', 'f_terms' => 'Términos y seña', 'f_privacy' => 'Privacidad',
 'terms_list' => [
  'Los precios del catálogo son indicativos. El precio final y las condiciones figuran en la proforma que emitimos para cada pedido.',
  'La proforma tiene una validez de 5 días corridos. Pasado ese plazo vence y hay que pedir una nueva.',
  'Para confirmar el pedido, el comprador paga una seña del 50 % del total mediante transferencia bancaria internacional o mediante otra forma de pago acordada por escrito (por ejemplo, una carta de crédito confirmada).',
  'Sin la seña acreditada en nuestra cuenta no se confirma el pedido, no se produce la mercadería ni se reserva el contenedor.',
  'El 50 % restante se paga antes de la carga del contenedor o contra copia del conocimiento de embarque (B/L), según indique la proforma.',
  'Si el comprador cancela después de pagar la seña, la seña no se devuelve: cubre costos de producción, packaging a medida y reserva de flete.',
  'Si no podemos entregar el pedido por causas nuestras, devolvemos la seña completa.',
  'Cumplimos las sanciones internacionales y las leyes de exportación vigentes. Si no podemos cumplirlas, podemos cancelar el pedido y devolver la seña.',
  'Ley aplicable: Argentina. Los Incoterms 2020 rigen la entrega indicada en la proforma. Las diferencias se resuelven primero por negociación y, si no hay acuerdo, ante los tribunales de la Ciudad de Buenos Aires.',
 ],
 'privacy_title' => 'Política de privacidad',
 'privacy_list' => [
  'Qué datos pedimos: tu nombre y email (desde tu cuenta de Google) y los datos de tu empresa: nombre, país, ciudad, cargo, número de registro comercial, WhatsApp y sitio web.',
  'Qué no pedimos: no pedimos tu documento de identidad personal ni guardamos tu contraseña. Google nunca nos envía tu contraseña.',
  'Para qué los usamos: verificar que tu empresa existe, preparar proformas, coordinar envíos y evitar pedidos falsos.',
  'Registros de seguridad: guardamos la fecha, la dirección IP, el país aproximado y el navegador de cada ingreso para detectar accesos sospechosos.',
  'Quién los ve: solo el equipo de ASHAB y de Rumbo Global Consultores S.A. No vendemos tus datos.',
  'Cuánto tiempo: mientras tengamos una relación comercial y hasta 10 años por obligaciones contables y aduaneras.',
  'Tus derechos: podés pedir acceso, corrección o eliminación de tus datos escribiéndonos por WhatsApp. En Argentina rige la Ley 25.326 de protección de datos personales.',
 ],
 'f_origin' => 'Producto de Argentina', 'f_rights' => 'Todos los derechos reservados.', 'f_by' => 'Proyecto asesorado por Rumbo Global Consultores S.A.',
 'wa_default' => 'Hola, quiero información sobre la yerba mate ASHAB.', 'wa_product' => 'Hola, quiero cotizar el producto %s de ASHAB.',
 'wa_order' => 'Hola, consulto por mi pedido %s de ASHAB.',
 'e_generic' => 'Ocurrió un error. Intentá de nuevo en unos minutos.', 'e_404' => 'No encontramos esa página.', 'back_home' => 'Volver al inicio',
],

'en' => [
 'tag' => 'Herbs of the Oasis · Argentine yerba mate',
 'nav_home' => 'Home', 'nav_products' => 'Products', 'nav_markets' => 'Markets', 'nav_contact' => 'Contact',
 'nav_login' => 'Log in', 'nav_panel' => 'My account', 'nav_logout' => 'Log out', 'nav_admin' => 'Administration',
 'hero_eyebrow' => 'Direct export from Misiones, Argentina',
 'hero_title' => 'Authentic Argentine yerba mate, on your table.',
 'hero_lead' => 'We connect the best producers in Misiones with distributors and wholesalers in Syria, Lebanon, Jordan and the United Arab Emirates. Consistent quality, bilingual packaging and fully documented shipments.',
 'hero_cta1' => 'Request a quote', 'hero_cta2' => 'See products',
 'why_title' => 'Why ASHAB?',
 'why1_t' => 'Argentine origin', 'why1_d' => 'Yerba from plantations and mills in Misiones, with batch traceability.',
 'why2_t' => 'Ready for the Arab market', 'why2_d' => 'Labels in Arabic, Spanish and English, and a taste adjusted to regional preferences.',
 'why3_t' => 'Documented shipments', 'why3_d' => 'Certificate of origin, sanitary certificates, insurance and secure payment.',
 'why4_t' => 'Long-term relationship', 'why4_d' => 'Regular supply and after-sales support for distributors.',
 'markets_title' => 'Our markets',
 'markets_lead' => 'We start with the countries where mate is already part of the table.',
 'm_sy' => 'Syria', 'm_sy_d' => 'The largest yerba mate consumer in the Middle East, with a deeply rooted tradition.',
 'm_lb' => 'Lebanon', 'm_lb_d' => 'Logistics gateway through the Port of Beirut, with strong consumption in the mountains and cities.',
 'm_jo' => 'Jordan', 'm_jo_d' => 'Access through the Port of Aqaba and communities that already drink mate.',
 'm_ae' => 'United Arab Emirates', 'm_ae_d' => 'Re-export hub through Jebel Ali and trade fairs such as Gulfood.',
 'markets_note' => 'Subject to the sanitary, customs and logistics conditions in force in each country.',
 'r_other' => 'Other',

 'products_title' => 'Products',
 'products_lead' => 'Presentations for wholesale distribution. Approved companies see prices and request quotes.',
 'price_locked_login' => 'Log in to see the price', 'price_locked_pending' => 'Available once your company is approved',
 'price_fob' => 'Indicative FOB price', 'pack' => 'Packaging', 'req_quote' => 'Request a quote',
 'qty_kg' => 'Quantity (kg)', 'qty_help' => 'Minimum 1,000 kg and maximum 48,000 kg per web order.', 'dest' => 'Destination country', 'incoterm' => 'Delivery term',
 'send_req' => 'Send request', 'quote_sent' => 'We received your request. You will get the proforma invoice in your account and on WhatsApp.',
 'qty_bad' => 'The quantity must be between 1,000 and 48,000 kg.', 'too_many' => 'You already have 3 open orders. Complete or cancel them before placing another.',
 'wa_ask' => 'Ask on WhatsApp', 'csrf' => 'Your session expired. Please try again.',

 'contact_title' => 'Contact', 'contact_lead' => 'Write to us and we will reply within 24 business hours.',
 'c_name' => 'Name', 'c_company' => 'Company', 'c_email' => 'Email', 'c_phone' => 'Phone (optional)', 'c_msg' => 'Message', 'c_send' => 'Send',
 'c_thanks' => 'Thank you! We received your message.', 'c_bad' => 'Please check name, email and message (minimum 10 characters).',
 'wa_title' => 'Write to us on WhatsApp', 'wa_intl' => 'Number in international format', 'wa_copy' => 'Copy number', 'wa_copied' => 'Copied',
 'wa_qr' => 'On a computer: scan the code with your phone.', 'wa_open' => 'Open WhatsApp',
 'wa_hours_t' => 'Opening hours', 'wa_hours' => 'Monday to Friday, 9:00 to 18:00 Argentina time. That is 15:00 to 24:00 in Syria, Jordan and Lebanon, and 16:00 to 01:00 in the UAE.',
 'wa_tip' => 'You do not need to dial zeros or local prefixes: the number already includes the country code (+54) and the 9 used for Argentine mobile phones.',

 'login_title' => 'Log in', 'login_lead' => 'Log in with your Google account. We do not store your password or ask for personal documents.',
 'login_google' => 'Continue with Google', 'login_why' => 'We use Google to verify your email and prevent fake orders. Then we ask for your company details.',
 'login_unavailable' => 'Google sign-in is not configured on this site yet.',
 'login_err_state' => 'We could not verify the sign-in. Please try again.', 'login_err_unverified' => 'Your Google account must have a verified email.',
 'login_err_denied' => 'You cancelled the Google sign-in.', 'login_blocked' => 'Too many attempts. Please try again later.', 'need_login' => 'Please log in to continue.',
 'logged_out' => 'You have logged out.', 'account_off' => 'Your account is disabled. Please write to us on WhatsApp.',

 'perfil_title' => 'Your company details', 'perfil_lead' => 'Fill them in once. We review them before enabling prices and orders. We do not ask for personal documents.',
 'f_company' => 'Company name', 'f_country' => 'Country', 'f_city' => 'City', 'f_role' => 'Your role in the company',
 'reg_SY' => 'Commercial registry number (Syria)', 'reg_LB' => 'Commercial registry number (Lebanon)', 'reg_JO' => 'Company registration number (Jordan)',
 'reg_AE' => 'Trade license number (UAE)', 'reg_OTRO' => 'Company registration or tax ID number',
 'f_whatsapp' => 'Company WhatsApp (with country code)', 'f_whatsapp_help' => 'Example: +971 50 123 4567', 'f_website' => 'Website (optional)', 'save' => 'Save',
 'perfil_saved' => 'Details saved. We will review your company.',
 'e_company' => 'Enter the company name.', 'e_city' => 'Enter the city.', 'e_regnum' => 'Enter the company registration number.',
 'e_whatsapp' => 'Enter a valid WhatsApp number with country code, for example +971501234567.',
 'acc_pendiente' => 'Under review', 'acc_aprobada' => 'Approved', 'acc_rechazada' => 'Rejected',
 'acc_pending_msg' => 'We are reviewing your company details (1 to 2 business days). Once approved you will see prices and be able to request quotes.',
 'acc_rejected_msg' => 'We could not approve your account. Please write to us on WhatsApp to clarify.', 'acc_notify' => 'Let us know on WhatsApp that I registered',
 'wa_registered' => 'Hello, I registered on ASHAB with the company %s (%s). Could you review my account?',
 'need_approval' => 'Your company has not been approved yet.',

 'panel_title' => 'My account', 'p_hello' => 'Hello', 'p_status' => 'Account status', 'p_orders' => 'My orders', 'p_none' => 'You have not placed any orders yet.',
 'p_date' => 'Date', 'p_product' => 'Product', 'p_qty' => 'Kg', 'p_dest' => 'Destination', 'p_total' => 'Total', 'p_open' => 'View', 'p_edit_profile' => 'Edit company details',

 'order' => 'Order', 'o_total' => 'Order total', 'o_sena' => 'Deposit to pay (50%)', 'o_saldo' => 'Balance (50%)', 'o_price' => 'Price per kg',
 'o_valid' => 'Proforma valid until', 'o_waiting' => 'We are preparing your proforma invoice. We will let you know on WhatsApp.',
 'o_how' => 'How to confirm your order',
 'o_step1' => '1. Review the proforma and the terms.', 'o_step2' => '2. Transfer 50% (the deposit) to the account shown.',
 'o_step3' => '3. Report the transfer on this page.', 'o_step4' => '4. Once the deposit is received, we confirm and start production.',
 'o_bank' => 'Bank transfer details', 'o_bank_missing' => 'We will send the bank details with the proforma on WhatsApp.',
 'o_pay_t' => 'Report the transfer', 'o_pay_bank' => 'Sending bank', 'o_pay_ref' => 'Reference or transaction number', 'o_pay_date' => 'Transfer date (YYYY-MM-DD)',
 'o_pay_amount' => 'Amount transferred (USD)', 'o_accept' => 'I accept the terms and the deposit policy (the deposit is not refundable if I cancel).',
 'o_pay_btn' => 'Report payment', 'o_pay_sent' => 'We received your payment notice. We will verify it at the bank and confirm.',
 'o_pay_bad' => 'Please check the payment details and accept the terms.', 'o_cancel' => 'Cancel order', 'o_cancelled' => 'Order cancelled.',
 'o_expired' => 'The proforma has expired. Please place a new order.', 'o_not_found' => 'We could not find that order.',
 'st_solicitada' => 'Request received', 'st_proforma_emitida' => 'Proforma issued: deposit pending', 'st_sena_informada' => 'Payment reported: we are verifying it',
 'st_sena_acreditada' => 'Deposit received: order confirmed', 'st_en_produccion' => 'In production', 'st_embarcada' => 'Shipped', 'st_cerrada' => 'Closed',
 'st_cancelada' => 'Cancelled', 'st_vencida' => 'Proforma expired',

 'terms_title' => 'Purchase terms and deposit policy', 'f_terms' => 'Terms and deposit', 'f_privacy' => 'Privacy',
 'terms_list' => [
  'Catalog prices are indicative. The final price and conditions appear on the proforma invoice we issue for each order.',
  'The proforma is valid for 5 calendar days. After that it expires and a new one must be requested.',
  'To confirm the order, the buyer pays a deposit of 50% of the total by international bank transfer, or by another payment method agreed in writing (for example, a confirmed letter of credit).',
  'Without the deposit received in our account the order is not confirmed, the goods are not produced and the container is not booked.',
  'The remaining 50% is paid before the container is loaded or against a copy of the bill of lading (B/L), as stated in the proforma.',
  'If the buyer cancels after paying the deposit, the deposit is not refunded: it covers production costs, custom packaging and freight booking.',
  'If we are unable to deliver the order for reasons on our side, we refund the full deposit.',
  'We comply with international sanctions and applicable export laws. If we cannot comply, we may cancel the order and refund the deposit.',
  'Governing law: Argentina. Incoterms 2020 govern the delivery stated in the proforma. Disputes are first settled by negotiation and, failing agreement, before the courts of the City of Buenos Aires.',
 ],
 'privacy_title' => 'Privacy policy',
 'privacy_list' => [
  'What we ask for: your name and email (from your Google account) and your company details: name, country, city, role, commercial registration number, WhatsApp and website.',
  'What we do not ask for: we do not ask for your personal identity document and we do not store your password. Google never sends us your password.',
  'What we use it for: verifying that your company exists, preparing proformas, coordinating shipments and preventing fake orders.',
  'Security logs: we record the date, IP address, approximate country and browser of each sign-in to detect suspicious access.',
  'Who sees it: only the ASHAB and Rumbo Global Consultores S.A. team. We do not sell your data.',
  'How long: while we have a business relationship and up to 10 years for accounting and customs obligations.',
  'Your rights: you can ask for access, correction or deletion of your data by writing to us on WhatsApp. In Argentina, Law 25,326 on personal data protection applies.',
 ],
 'f_origin' => 'Product of Argentina', 'f_rights' => 'All rights reserved.', 'f_by' => 'Project advised by Rumbo Global Consultores S.A.',
 'wa_default' => 'Hello, I would like information about ASHAB yerba mate.', 'wa_product' => 'Hello, I would like a quote for the ASHAB product: %s.',
 'wa_order' => 'Hello, I am writing about my ASHAB order %s.',
 'e_generic' => 'Something went wrong. Please try again in a few minutes.', 'e_404' => 'We could not find that page.', 'back_home' => 'Back to home',
],

'ar' => [
 'tag' => 'أعشاب الواحة · المتة الأرجنتينية',
 'nav_home' => 'الرئيسية', 'nav_products' => 'المنتجات', 'nav_markets' => 'الأسواق', 'nav_contact' => 'اتصل بنا',
 'nav_login' => 'تسجيل الدخول', 'nav_panel' => 'حسابي', 'nav_logout' => 'خروج', 'nav_admin' => 'الإدارة',
 'hero_eyebrow' => 'تصدير مباشر من ميسيونيس، الأرجنتين',
 'hero_title' => 'المتة الأرجنتينية الأصيلة إلى مائدتكم.',
 'hero_lead' => 'نربط أفضل المنتجين في ميسيونيس بالموزعين وتجار الجملة في سوريا ولبنان والأردن والإمارات العربية المتحدة. جودة ثابتة وتغليف ثنائي اللغة وشحنات موثقة بالكامل.',
 'hero_cta1' => 'اطلب عرض سعر', 'hero_cta2' => 'تصفح المنتجات',
 'why_title' => 'لماذا أعشاب الواحة؟',
 'why1_t' => 'أصل أرجنتيني', 'why1_d' => 'متة من مزارع ومطاحن ميسيونيس مع إمكانية تتبع كل دفعة.',
 'why2_t' => 'جاهزة للسوق العربية', 'why2_d' => 'بطاقات بيانات بالعربية والإسبانية والإنجليزية ونكهة تناسب ذوق المنطقة.',
 'why3_t' => 'شحنات موثقة', 'why3_d' => 'شهادة المنشأ والشهادات الصحية والتأمين ودفع آمن.',
 'why4_t' => 'علاقة طويلة الأمد', 'why4_d' => 'إمداد منتظم ودعم ما بعد البيع للموزعين.',
 'markets_title' => 'أسواقنا',
 'markets_lead' => 'نبدأ من البلدان التي أصبحت فيها المتة جزءاً من المائدة.',
 'm_sy' => 'سوريا', 'm_sy_d' => 'أكبر مستهلك للمتة في الشرق الأوسط وتقليد راسخ منذ زمن طويل.',
 'm_lb' => 'لبنان', 'm_lb_d' => 'بوابة لوجستية عبر مرفأ بيروت واستهلاك قوي في الجبل والمدن.',
 'm_jo' => 'الأردن', 'm_jo_d' => 'منفذ عبر ميناء العقبة وجاليات تشرب المتة أصلاً.',
 'm_ae' => 'الإمارات العربية المتحدة', 'm_ae_d' => 'مركز لإعادة التصدير عبر جبل علي ومعارض مثل غلفود.',
 'markets_note' => 'يخضع ذلك للشروط الصحية والجمركية واللوجستية السارية في كل بلد.',
 'r_other' => 'أخرى',

 'products_title' => 'المنتجات',
 'products_lead' => 'عبوات مخصصة للتوزيع بالجملة. الشركات المعتمدة ترى الأسعار وتطلب عروض الأسعار.',
 'price_locked_login' => 'سجّل الدخول لعرض السعر', 'price_locked_pending' => 'يظهر السعر بعد اعتماد شركتك',
 'price_fob' => 'سعر FOB استرشادي', 'pack' => 'العبوة', 'req_quote' => 'اطلب عرض سعر',
 'qty_kg' => 'الكمية (كغ)', 'qty_help' => 'الحد الأدنى 1000 كغ والحد الأقصى 48000 كغ للطلب عبر الموقع.', 'dest' => 'بلد الوجهة', 'incoterm' => 'شروط التسليم',
 'send_req' => 'إرسال الطلب', 'quote_sent' => 'تم استلام طلبك. ستصلك الفاتورة المبدئية في حسابك وعبر واتساب.',
 'qty_bad' => 'يجب أن تكون الكمية بين 1000 و48000 كغ.', 'too_many' => 'لديك 3 طلبات مفتوحة. أكملها أو ألغها قبل تقديم طلب جديد.',
 'wa_ask' => 'اسأل عبر واتساب', 'csrf' => 'انتهت الجلسة. يرجى المحاولة مرة أخرى.',

 'contact_title' => 'اتصل بنا', 'contact_lead' => 'راسلنا وسنرد خلال 24 ساعة عمل.',
 'c_name' => 'الاسم', 'c_company' => 'الشركة', 'c_email' => 'البريد الإلكتروني', 'c_phone' => 'الهاتف (اختياري)', 'c_msg' => 'الرسالة', 'c_send' => 'إرسال',
 'c_thanks' => 'شكراً لك! تم استلام رسالتك.', 'c_bad' => 'يرجى التحقق من الاسم والبريد والرسالة (10 أحرف على الأقل).',
 'wa_title' => 'راسلنا عبر واتساب', 'wa_intl' => 'الرقم بالصيغة الدولية', 'wa_copy' => 'نسخ الرقم', 'wa_copied' => 'تم النسخ',
 'wa_qr' => 'من الحاسوب: امسح الرمز بهاتفك.', 'wa_open' => 'افتح واتساب',
 'wa_hours_t' => 'ساعات العمل', 'wa_hours' => 'من الإثنين إلى الجمعة، من 9 صباحاً إلى 6 مساءً بتوقيت الأرجنتين. أي من 3 عصراً إلى منتصف الليل في سوريا والأردن ولبنان، ومن 4 عصراً إلى 1 بعد منتصف الليل في الإمارات.',
 'wa_tip' => 'لا حاجة لإضافة أصفار أو مفاتيح محلية: الرقم يتضمن رمز الدولة (+54) والرقم 9 الخاص بالهواتف المحمولة في الأرجنتين.',

 'login_title' => 'تسجيل الدخول', 'login_lead' => 'سجّل الدخول بحساب جوجل. لا نحتفظ بكلمة مرورك ولا نطلب وثائق شخصية.',
 'login_google' => 'المتابعة باستخدام جوجل', 'login_why' => 'نستخدم جوجل للتحقق من بريدك الإلكتروني ومنع الطلبات الوهمية. بعد ذلك نطلب بيانات شركتك.',
 'login_unavailable' => 'تسجيل الدخول عبر جوجل غير مفعّل في هذا الموقع بعد.',
 'login_err_state' => 'تعذّر التحقق من تسجيل الدخول. حاول مرة أخرى.', 'login_err_unverified' => 'يجب أن يكون بريد حساب جوجل موثّقاً.',
 'login_err_denied' => 'لقد ألغيت تسجيل الدخول عبر جوجل.', 'login_blocked' => 'محاولات كثيرة. حاول لاحقاً.', 'need_login' => 'يرجى تسجيل الدخول للمتابعة.',
 'logged_out' => 'تم تسجيل الخروج.', 'account_off' => 'حسابك معطّل. راسلنا عبر واتساب.',

 'perfil_title' => 'بيانات شركتك', 'perfil_lead' => 'أكملها مرة واحدة. نراجعها قبل تفعيل الأسعار والطلبات. لا نطلب وثائق شخصية.',
 'f_company' => 'اسم الشركة', 'f_country' => 'البلد', 'f_city' => 'المدينة', 'f_role' => 'منصبك في الشركة',
 'reg_SY' => 'رقم السجل التجاري (سوريا)', 'reg_LB' => 'رقم السجل التجاري (لبنان)', 'reg_JO' => 'رقم تسجيل الشركة (الأردن)',
 'reg_AE' => 'رقم الرخصة التجارية (الإمارات)', 'reg_OTRO' => 'رقم السجل التجاري أو الرقم الضريبي للشركة',
 'f_whatsapp' => 'واتساب الشركة (مع رمز الدولة)', 'f_whatsapp_help' => 'مثال: ‎+971 50 123 4567', 'f_website' => 'الموقع الإلكتروني (اختياري)', 'save' => 'حفظ',
 'perfil_saved' => 'تم حفظ البيانات. سنراجع شركتك.',
 'e_company' => 'أدخل اسم الشركة.', 'e_city' => 'أدخل المدينة.', 'e_regnum' => 'أدخل رقم تسجيل الشركة.',
 'e_whatsapp' => 'أدخل رقم واتساب صحيحاً مع رمز الدولة، مثل ‎+971501234567.',
 'acc_pendiente' => 'قيد المراجعة', 'acc_aprobada' => 'معتمدة', 'acc_rechazada' => 'مرفوضة',
 'acc_pending_msg' => 'نراجع بيانات شركتك (من يوم إلى يومي عمل). بعد الاعتماد سترى الأسعار ويمكنك طلب عروض الأسعار.',
 'acc_rejected_msg' => 'لم نتمكن من اعتماد حسابك. راسلنا عبر واتساب للتوضيح.', 'acc_notify' => 'أبلغنا عبر واتساب أنني سجّلت',
 'wa_registered' => 'مرحباً، سجّلت في أعشاب الواحة باسم الشركة %s (%s). هل يمكنكم مراجعة حسابي؟',
 'need_approval' => 'لم يتم اعتماد شركتك بعد.',

 'panel_title' => 'حسابي', 'p_hello' => 'مرحباً', 'p_status' => 'حالة الحساب', 'p_orders' => 'طلباتي', 'p_none' => 'لم تقدّم أي طلبات بعد.',
 'p_date' => 'التاريخ', 'p_product' => 'المنتج', 'p_qty' => 'كغ', 'p_dest' => 'الوجهة', 'p_total' => 'الإجمالي', 'p_open' => 'عرض', 'p_edit_profile' => 'تعديل بيانات الشركة',

 'order' => 'الطلب', 'o_total' => 'إجمالي الطلب', 'o_sena' => 'العربون المطلوب (50%)', 'o_saldo' => 'الرصيد (50%)', 'o_price' => 'السعر للكيلوغرام',
 'o_valid' => 'الفاتورة المبدئية صالحة حتى', 'o_waiting' => 'نجهّز فاتورتك المبدئية. سنبلغك عبر واتساب.',
 'o_how' => 'كيف تؤكد طلبك',
 'o_step1' => '1. راجع الفاتورة المبدئية والشروط.', 'o_step2' => '2. حوّل 50% (العربون) إلى الحساب المبيّن.',
 'o_step3' => '3. أبلغنا بالتحويل في هذه الصفحة.', 'o_step4' => '4. عند استلام العربون نؤكد الطلب ونبدأ الإنتاج.',
 'o_bank' => 'بيانات التحويل المصرفي', 'o_bank_missing' => 'سنرسل البيانات المصرفية مع الفاتورة المبدئية عبر واتساب.',
 'o_pay_t' => 'الإبلاغ عن التحويل', 'o_pay_bank' => 'البنك المحوِّل', 'o_pay_ref' => 'المرجع أو رقم العملية', 'o_pay_date' => 'تاريخ التحويل (سنة-شهر-يوم)',
 'o_pay_amount' => 'المبلغ المحوّل (دولار أمريكي)', 'o_accept' => 'أوافق على الشروط وسياسة العربون (العربون غير مسترد إذا ألغيت الطلب).',
 'o_pay_btn' => 'إبلاغ عن الدفع', 'o_pay_sent' => 'تم استلام إشعار الدفع. سنتحقق منه في البنك ونؤكد لك.',
 'o_pay_bad' => 'يرجى التحقق من بيانات الدفع وقبول الشروط.', 'o_cancel' => 'إلغاء الطلب', 'o_cancelled' => 'تم إلغاء الطلب.',
 'o_expired' => 'انتهت صلاحية الفاتورة المبدئية. قدّم طلباً جديداً.', 'o_not_found' => 'لم نجد هذا الطلب.',
 'st_solicitada' => 'تم استلام الطلب', 'st_proforma_emitida' => 'صدرت الفاتورة المبدئية: العربون مطلوب', 'st_sena_informada' => 'تم الإبلاغ عن الدفع: نتحقق منه',
 'st_sena_acreditada' => 'تم استلام العربون: الطلب مؤكد', 'st_en_produccion' => 'قيد الإنتاج', 'st_embarcada' => 'تم الشحن', 'st_cerrada' => 'مكتمل',
 'st_cancelada' => 'ملغى', 'st_vencida' => 'انتهت صلاحية الفاتورة',

 'terms_title' => 'شروط الشراء وسياسة العربون', 'f_terms' => 'الشروط والعربون', 'f_privacy' => 'الخصوصية',
 'terms_list' => [
  'أسعار الكتالوج استرشادية. السعر النهائي والشروط مذكورة في الفاتورة المبدئية التي نصدرها لكل طلب.',
  'الفاتورة المبدئية صالحة لمدة 5 أيام متتالية. بعد ذلك تنتهي صلاحيتها ويجب طلب فاتورة جديدة.',
  'لتأكيد الطلب يدفع المشتري عربوناً قدره 50% من الإجمالي بحوالة مصرفية دولية، أو بوسيلة دفع أخرى متفق عليها كتابةً (مثل اعتماد مستندي مؤكَّد).',
  'بدون وصول العربون إلى حسابنا لا يتم تأكيد الطلب ولا يبدأ إنتاج البضاعة ولا يُحجز الحاوية.',
  'يُدفع الـ50% المتبقية قبل تحميل الحاوية أو مقابل نسخة من بوليصة الشحن (B/L) بحسب ما تنص عليه الفاتورة المبدئية.',
  'إذا ألغى المشتري الطلب بعد دفع العربون فلا يُسترد العربون، لأنه يغطي تكاليف الإنتاج والتغليف الخاص وحجز الشحن.',
  'إذا تعذّر علينا تسليم الطلب لأسباب من جانبنا نعيد العربون كاملاً.',
  'نلتزم بالعقوبات الدولية وقوانين التصدير السارية. إذا تعذّر علينا الالتزام بها يجوز لنا إلغاء الطلب وإعادة العربون.',
  'القانون الواجب التطبيق: قانون الأرجنتين. تحكم قواعد الإنكوترمز 2020 التسليم المذكور في الفاتورة المبدئية. تُحل الخلافات أولاً بالتفاوض، وإن لم يتم الاتفاق فأمام محاكم مدينة بوينس آيرس.',
 ],
 'privacy_title' => 'سياسة الخصوصية',
 'privacy_list' => [
  'ما نطلبه: اسمك وبريدك الإلكتروني (من حساب جوجل) وبيانات شركتك: الاسم والبلد والمدينة والمنصب ورقم السجل التجاري وواتساب والموقع الإلكتروني.',
  'ما لا نطلبه: لا نطلب وثيقة هويتك الشخصية ولا نحتفظ بكلمة مرورك. جوجل لا ترسل إلينا كلمة مرورك أبداً.',
  'سبب الاستخدام: التحقق من وجود شركتك وإعداد الفواتير المبدئية وتنسيق الشحنات ومنع الطلبات الوهمية.',
  'سجلات الأمان: نسجّل تاريخ كل دخول وعنوان IP والبلد التقريبي والمتصفح لاكتشاف أي وصول مشبوه.',
  'من يطّلع عليها: فريق أعشاب الواحة وشركة رومبو غلوبال للاستشارات فقط. لا نبيع بياناتك.',
  'مدة الاحتفاظ: طوال العلاقة التجارية وحتى 10 سنوات للالتزامات المحاسبية والجمركية.',
  'حقوقك: يمكنك طلب الاطلاع على بياناتك أو تصحيحها أو حذفها بمراسلتنا عبر واتساب. يسري في الأرجنتين القانون 25326 لحماية البيانات الشخصية.',
 ],
 'f_origin' => 'منتج الأرجنتين', 'f_rights' => 'جميع الحقوق محفوظة.', 'f_by' => 'مشروع بإشراف شركة رومبو غلوبال للاستشارات',
 'wa_default' => 'مرحباً، أرغب في الحصول على معلومات حول متة أعشاب الواحة.', 'wa_product' => 'مرحباً، أرغب في عرض سعر للمنتج: %s.',
 'wa_order' => 'مرحباً، أستفسر عن طلبي %s من أعشاب الواحة.',
 'e_generic' => 'حدث خطأ. حاول مرة أخرى بعد دقائق.', 'e_404' => 'لم نجد هذه الصفحة.', 'back_home' => 'العودة إلى الرئيسية',
],
];

function idioma_inicial(): string
{
    $ok = array_keys(IDIOMAS);
    if (isset($_GET['lang']) && in_array($_GET['lang'], $ok, true)) {
        setcookie('lang', $_GET['lang'], ['expires' => time() + 31536000, 'path' => '/', 'samesite' => 'Lax', 'secure' => is_https()]);
        return $_SESSION['lang'] = $_GET['lang'];
    }
    if (!empty($_SESSION['lang']) && in_array($_SESSION['lang'], $ok, true)) {
        return $_SESSION['lang'];
    }
    if (!empty($_COOKIE['lang']) && in_array($_COOKIE['lang'], $ok, true)) {
        return $_SESSION['lang'] = $_COOKIE['lang'];
    }
    $al = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '', 0, 2));
    return $_SESSION['lang'] = in_array($al, $ok, true) ? $al : 'en';
}

function lang(): string { return $GLOBALS['LANG']; }
function es_rtl(): bool { return lang() === 'ar'; }

function t(string $k, ...$args): string
{
    $s = $GLOBALS['TR'][lang()][$k] ?? $GLOBALS['TR']['es'][$k] ?? $k;
    if (is_array($s)) {
        return $k;
    }
    return $args ? vsprintf($s, $args) : $s;
}

/** Lista de párrafos traducidos. */
function tl(string $k): array
{
    $s = $GLOBALS['TR'][lang()][$k] ?? $GLOBALS['TR']['es'][$k] ?? [];
    return is_array($s) ? $s : [];
}
