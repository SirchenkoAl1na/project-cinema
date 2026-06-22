function generatePDF(filmTitle, date, time,hole,row,place, price,ticket_kod,qr_token) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    doc.addFileToVFS('Roboto-Regular.ttf', ROBOTO_BASE64); 
    doc.addFont('Roboto-Regular.ttf', 'Roboto', 'normal'); 
    doc.setFont('Roboto');

    doc.setFontSize(16);
    doc.text("Фільм: " + filmTitle, 20, 20);

    doc.setFontSize(12);
    doc.text("Дата: " + date, 20, 30);
    doc.text("Час: " + time, 20, 40);
    doc.text("Зала: " + hole, 20, 50);
    doc.text("Ряд: " + row, 20, 60);
    doc.text("Місце: " + place, 20, 70);
    doc.text("Ціна: " + price + " грн", 20, 80);
    // doc.text("Код квитка: " + ticket_kod, 20, 90);

     // 👉 генеруємо QR в canvas
    const qrContainer = document.getElementById("qr_temp");
    qrContainer.innerHTML = "";

    const qr = new QRCode(qrContainer, {
        text: qr_token,
        width: 200,
        height: 200
    });

    // 👉 беремо canvas
    const canvas = qrContainer.querySelector("canvas");

    const imgData = canvas.toDataURL("image/png");

    doc.addImage(imgData, "PNG", 130, 20, 50, 50);

    doc.save("Квиток.pdf");
}