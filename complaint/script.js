const coordinatorByCategory = {
  electricity: "Electricity maintenance coordinator",
  washroom: "Sanitation & plumbing coordinator",
  carpenter: "Carpentry & furniture coordinator",
  other: "Hostel warden / general coordinator"
};

const STORAGE_KEY = "hostelComplaintsV1";

const complaints = [];
let complaintIdCounter = 1;

const form = document.getElementById("complaintForm");
const toastEl = document.getElementById("toast");
const lastComplaintInfoEl = document.getElementById("lastComplaintInfo");

const idModalBackdrop = document.getElementById("idModalBackdrop");
const idModal = document.getElementById("idModal");
const idModalText = document.getElementById("idModalText");
const idModalOkBtn = document.getElementById("idModalOkBtn");
const copyIdBtn = document.getElementById("copyIdBtn");

/* ================= TOAST ================= */
function showToast(message) {
  if (!toastEl) return;
  toastEl.textContent = message;
  toastEl.classList.add("visible");
  setTimeout(() => {
    toastEl.classList.remove("visible");
  }, 2400);
}

/* ================= STORAGE ================= */
function loadComplaintsFromStorage() {
  try {
    const raw = localStorage.getItem(STORAGE_KEY);
    if (!raw) return;

    const parsed = JSON.parse(raw);
    const items = Array.isArray(parsed.items) ? parsed.items : [];

    complaintIdCounter =
      typeof parsed.lastId === "number" && parsed.lastId > 0
        ? parsed.lastId
        : items.length + 1;

    items.forEach((item) => {
      complaints.push({
        ...item,
        createdAt: item.createdAt ? new Date(item.createdAt) : new Date(),
        resolvedAt: item.resolvedAt ? new Date(item.resolvedAt) : null
      });
    });
  } catch (err) {
    console.error("Failed to load complaints", err);
  }
}

function persistComplaintsToStorage() {
  const payload = {
    lastId: complaintIdCounter,
    items: complaints.map((c) => ({
      ...c,
      createdAt: c.createdAt ? c.createdAt.toISOString() : null,
      resolvedAt: c.resolvedAt ? c.resolvedAt.toISOString() : null
    }))
  };
  localStorage.setItem(STORAGE_KEY, JSON.stringify(payload));
}

/* ================= MODAL ================= */
function openIdModal(id) {
  if (!idModal || !idModalBackdrop || !idModalText) return;

  idModalText.textContent = "#" + id;

  idModal.classList.add("visible");
  idModalBackdrop.classList.add("visible");
}

function closeIdModal() {
  idModal.classList.remove("visible");
  idModalBackdrop.classList.remove("visible");
}

if (idModalOkBtn) {
  idModalOkBtn.addEventListener("click", closeIdModal);
}

if (idModalBackdrop) {
  idModalBackdrop.addEventListener("click", closeIdModal);
}

/* ================= COPY BUTTON ================= */
if (copyIdBtn) {
  copyIdBtn.addEventListener("click", () => {
    const idText = idModalText.textContent.replace("#", "");
    navigator.clipboard.writeText(idText);
    showToast("Complaint ID copied!");
  });
}

/* ================= FORM SUBMIT ================= */
if (form) {
  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const studentName = document.getElementById("studentName").value.trim();
    const roomNumber = document.getElementById("roomNumber").value.trim();
    const hostelBlock = document.getElementById("hostelBlock").value.trim();
    const email = document.getElementById("email").value.trim();
    const category = document.getElementById("category").value;
    const priority = document.getElementById("priority").value;
    const details = document.getElementById("details").value.trim();

    if (!studentName || !roomNumber || !hostelBlock || !category || !details) {
      showToast("Please fill all required fields.");
      return;
    }

    const complaint = {
      id: complaintIdCounter++,
      studentName,
      roomNumber,
      hostelBlock,
      email,
      category,
      priority,
      details,
      createdAt: new Date(),
      status: "Pending",
      coordinator: coordinatorByCategory[category] || "Hostel warden",
      resolvedAt: null,
      rating: "",
      feedback: ""
    };

    complaints.unshift(complaint);
    persistComplaintsToStorage();

    form.reset();
    document.getElementById("priority").value = "normal";

    if (lastComplaintInfoEl) {
      lastComplaintInfoEl.textContent =
        "Your complaint ID is #" +
        complaint.id +
        ". Use this ID on the status page.";
    }

    /* 🔥 OPEN CENTER POPUP */
    openIdModal(complaint.id);

    showToast("Complaint submitted successfully!");
  });
}

loadComplaintsFromStorage();
function openIdModal(id) {
  idModalText.textContent = "#" + id;
  idModal.classList.add("visible");
  idModalBackdrop.classList.add("visible");
}

function closeIdModal() {
  idModal.classList.remove("visible");
  idModalBackdrop.classList.remove("visible");
}

idModalOkBtn.addEventListener("click", closeIdModal);
idModalBackdrop.addEventListener("click", closeIdModal);
openIdModal(complaint.id);