document.addEventListener("DOMContentLoaded", function () {
    const actionModal = document.getElementById("actionModal");
    const assignForm = document.getElementById("assignForm");
    const vacateSection = document.getElementById("vacateSection");
    const actionTitle = document.getElementById("actionTitle");

    const roomNumberInput = document.getElementById("roomNumber");
    const roomBlockInput = document.getElementById("roomBlock");
    const roomFloorInput = document.getElementById("roomFloor");
    
    const vacateBtn = document.getElementById("vacateBtn");
    const closeAction = document.getElementById("closeAction");
    
    const roomMapModal = document.getElementById("roomMapModal");
    const viewMapBtn = document.getElementById("viewMapBtn");
    
    console.log(assignForm);
    // Show Room Map Modal
    viewMapBtn.addEventListener("click", () => {
        roomMapModal.style.display = "flex";
    });

    // Close Room Map Modal
    roomMapModal.querySelector(".modal-close").addEventListener("click", () => {
        roomMapModal.style.display = "none";
    });

    // Event delegation: click on any room-box
    roomMapModal.addEventListener("click", function(e) {
        const box = e.target.closest(".room-box");
        if (!box) return;

        const room = box.dataset.room;
        const block = box.dataset.block;
        const floor = box.dataset.floor;
        const student = box.dataset.student; // empty string = vacant

        // Open Action Modal
        actionModal.style.display = "flex";

        // Fill hidden fields
        roomNumberInput.value = room;
        roomBlockInput.value = block;
        roomFloorInput.value = floor;

        if(student === "") {
            // VACANT ROOM
            actionTitle.innerText = `Assign Room ${room}`;
            assignForm.style.display = "block";
            vacateSection.style.display = "none";
        } else {
            // TAKEN ROOM
            actionTitle.innerText = `Vacate Room ${room}`;
            assignForm.style.display = "none";
            vacateSection.style.display = "block";

            // Remove old listener safely
            const newVacateBtn = vacateBtn.cloneNode(true);
            vacateBtn.parentNode.replaceChild(newVacateBtn, vacateBtn);

            newVacateBtn.addEventListener("click", () => {
                fetch("vacate_room.php", {
                    method: "POST",
                    headers: {"Content-Type":"application/x-www-form-urlencoded"},
                    body: `room_number=${room}&block=${block}&floor=${floor}`
                })
                .then(res => res.text())
                .then(() => {
                    actionModal.style.display = "none";
                    location.reload(); // refresh table & map
                });
            });
        }
    });

    // Assign Room Form
assignForm.addEventListener("submit", function(e) {
    e.preventDefault();

    fetch("assign_room.php", {
        method: "POST",
        body: new FormData(assignForm)
    })
    .then(res => res.text())
    .then(data => {

        if (data.trim() === "success") {
            alert("Room Allocated Successfully!");
            location.reload();
        } 
        else if (data.trim() === "already") {
            alert("Room already allocated!");
        }
        else {
            alert("Allocation failed!");
        }

    });
});
    // Close Action Modal
    closeAction.addEventListener("click", () => {
        actionModal.style.display = "none";
    });

    window.addEventListener("click", (e) => {
        if(e.target === actionModal) actionModal.style.display = "none";
    });

});