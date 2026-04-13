document.addEventListener("DOMContentLoaded", () => {
  const body = document.body;
  const signInForm = document.getElementById("sign-in-form");
  const nameInput = document.getElementById("signin-name");
  const roleSelect = document.getElementById("signin-role");
  const passwordInput = document.getElementById("signin-password");
  const topSignInLink = document.getElementById("top-signin-link");
  const signedInInfo = document.getElementById("signed-in-info");
  const signedInLabel = document.getElementById("signed-in-label");
  const signOutBtn = document.getElementById("sign-out-btn");
  const announcementsList = document.getElementById("announcements-list");
  const addAnnouncementForm = document.getElementById("add-announcement-form");
  const announcementModal = document.getElementById("announcement-modal");
  const openAnnouncementModalBtn = document.getElementById("open-announcement-modal");
  const closeAnnouncementModalBtn = document.getElementById("close-announcement-modal");
  const openRoomsMapBtn = document.getElementById("open-rooms-map");
  const roomsMapModal = document.getElementById("rooms-map-modal");
  const closeRoomsMapBtn = document.getElementById("close-rooms-map");
  const roomsFilterFloor = document.getElementById("rooms-filter-floor");
  const roomsFilterBlock = document.getElementById("rooms-filter-block");
  const roomsFilterRoom = document.getElementById("rooms-filter-room");
  const roomsFilterFloorMain = document.getElementById("rooms-filter-floor-main");
  const roomsFilterBlockMain = document.getElementById("rooms-filter-block-main");
  const roomsFilterRoomMain = document.getElementById("rooms-filter-room-main");

  let editingAnnouncement = null;

  const STORAGE_KEY = "hostelUser";

  // ================= ROOM DATA (for rooms.html) =================
  // Floors 1–4, rooms 01–90 on each floor.
  // Each floor has blocks A, B, C; each block has 2 subblocks of 15 rooms.
  const ROOMS = (() => {
    const blocks = ["A", "B", "C"];
    const rooms = [];

    for (let floor = 1; floor <= 4; floor++) {
      blocks.forEach((block, blockIndex) => {
        for (let subblock = 1; subblock <= 2; subblock++) {
          for (let i = 1; i <= 15; i++) {
            const offsetWithinFloor = blockIndex * 30 + (subblock - 1) * 15 + i; // 1–90
            const roomNumber = floor * 100 + offsetWithinFloor; // e.g. 101–190, 201–290...

            // Simple pattern: about 2/3 taken, 1/3 vacant
            const status = roomNumber % 3 === 0 ? "vacant" : "occupied";

            rooms.push({
              number: String(roomNumber),
              floor,
              block,
              subblock,
              capacity: 2,
              status,
            });
          }
        }
      });
    }

    return rooms;
  })();

  function renderAttachment(file, container) {
    if (!file || !container) return;

    // Clear any existing content
    container.innerHTML = "";

    const url = URL.createObjectURL(file);

    if (file.type.startsWith("image/")) {
      const img = document.createElement("img");
      img.src = url;
      img.alt = file.name || "Announcement attachment";
      container.appendChild(img);
    } else {
      const link = document.createElement("a");
      link.href = url;
      link.target = "_blank";
      link.rel = "noopener noreferrer";
      link.textContent = file.name || "View attachment";
      link.className = "announcement-file-link";
      container.appendChild(link);
    }
  }

  function formatDateTime() {
    const now = new Date();
    const date = now.toLocaleDateString(undefined, {
      year: "numeric",
      month: "short",
      day: "2-digit",
    });
    const time = now.toLocaleTimeString(undefined, {
      hour: "2-digit",
      minute: "2-digit",
    });
    return `${date} • ${time}`;
  }

  function loadUser() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      return raw ? JSON.parse(raw) : null;
    } catch {
      return null;
    }
  }

  function saveUser(user) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(user));
  }

  function clearUser() {
    localStorage.removeItem(STORAGE_KEY);
  }

  // ================= RENDER ROOMS (rooms.html) =================

  const roomsFilterState = {
    floor: "all",
    block: "all",
    room: "all",
  };

  function renderRooms() {
    const roomsTable = document.getElementById("rooms-table");
    const totalEl = document.getElementById("rooms-total");
    const occupiedEl = document.getElementById("rooms-occupied");
    const vacantEl = document.getElementById("rooms-vacant");
    const roomsMap = document.getElementById("rooms-map");

    if (!roomsTable || !totalEl || !occupiedEl || !vacantEl) return;

    const tbody = roomsTable.querySelector("tbody");
    if (!tbody) return;

    tbody.innerHTML = "";
    if (roomsMap) {
      roomsMap.innerHTML = "";
    }

    // Global summary counts (not affected by filters)
    let globalOccupied = 0;
    let globalVacant = 0;
    ROOMS.forEach((room) => {
      if (room.status === "occupied") globalOccupied++;
      else globalVacant++;
    });

    // Apply filters for table + map
    const filteredRooms = ROOMS.filter((room) => {
      if (roomsFilterState.floor !== "all" && String(room.floor) !== roomsFilterState.floor) {
        return false;
      }
      if (roomsFilterState.block !== "all" && room.block !== roomsFilterState.block) {
        return false;
      }
      if (roomsFilterState.room !== "all" && room.number !== roomsFilterState.room) {
        return false;
      }
      return true;
    });

    let lastFloor = null;
    let lastBlock = null;
    let lastSubblock = null;

    


    filteredRooms.forEach((room) => {
      let roomIndex = 0;
      filteredRooms.forEach((room) => {

        if (roomsMap && roomIndex > 0) {

          // Every 15 rooms = new row
          if (roomIndex % 15 === 0) {

            const rowNumber = roomIndex / 15;

            // 🔵 Every 6 rows (separate floors)
            if (rowNumber % 6 === 0) {
              const floorSpacer = document.createElement("div");
              floorSpacer.style.gridColumn = "1 / -1";
              floorSpacer.style.height = "30px";
              roomsMap.appendChild(floorSpacer);
            }
            // 🟢 Every 2 rows (separate blocks)
            else if (rowNumber % 2 === 0) {
              const blockSpacer = document.createElement("div");
              blockSpacer.style.gridColumn = "1 / -1";
              blockSpacer.style.height = "15px";
              roomsMap.appendChild(blockSpacer);
            }
          }
        }

        // Create room circle
        if (roomsMap) {
          const circle = document.createElement("div");
          circle.className = `room-circle ${room.status}`;
          circle.textContent = room.number;
          roomsMap.appendChild(circle);
        }

        roomIndex++;
      });     
      
      
    });

    const totalCount = ROOMS.length;
    totalEl.textContent = totalCount;
    occupiedEl.textContent = globalOccupied;
    vacantEl.textContent = globalVacant;
  }

  function applyUserState(user) {
    const isLoggedIn = !!user;
    const isAdmin = isLoggedIn && user.role === "admin";
    const isStudent = isLoggedIn && user.role === "student";

    body.classList.toggle("admin-mode", !!isAdmin);
    body.classList.toggle("student-mode", !!isStudent);

    if (signedInInfo && signedInLabel) {
      if (isLoggedIn) {
        signedInLabel.textContent = `${user.name} (${isAdmin ? "Admin" : "Student"})`;
        signedInInfo.hidden = false;
        if (topSignInLink) topSignInLink.hidden = true;
      } else {
        signedInInfo.hidden = true;
        if (topSignInLink) topSignInLink.hidden = false;
      }
    }

    if (signInForm && !signedInInfo) {
      // On standalone sign-in page, we don't show any "signed in" banner,
      // just keep the form visible so user can change account.
      if (isLoggedIn) {
        nameInput.value = user.name;
        roleSelect.value = user.role;
      }
    }
  }

  const existingUser = loadUser();
  if (existingUser) {
    applyUserState(existingUser);
  }

  // Render room allocation if on rooms.html
  renderRooms();

  // Rooms map modal open/close
  if (openRoomsMapBtn && roomsMapModal) {
    openRoomsMapBtn.addEventListener("click", () => {
      // Reset filters each time the map is opened
      roomsFilterState.floor = "all";
      roomsFilterState.block = "all";
      roomsFilterState.room = "all";
      if (roomsFilterFloor) roomsFilterFloor.value = "all";
      if (roomsFilterBlock) roomsFilterBlock.value = "all";
      if (roomsFilterRoom) roomsFilterRoom.value = "all";
      renderRooms();
      roomsMapModal.hidden = false;
    });
  }

  if (closeRoomsMapBtn && roomsMapModal) {
    closeRoomsMapBtn.addEventListener("click", () => {
      roomsMapModal.hidden = true;
    });
  }

  if (roomsMapModal) {
    roomsMapModal.addEventListener("click", (e) => {
      if (e.target === roomsMapModal) {
        roomsMapModal.hidden = true;
      }
    });
  }

  // Rooms filters
  function syncFilterSelects(type, value) {
    if (type === "floor") {
      if (roomsFilterFloor && roomsFilterFloor.value !== value) roomsFilterFloor.value = value;
      if (roomsFilterFloorMain && roomsFilterFloorMain.value !== value) roomsFilterFloorMain.value = value;
    } else if (type === "block") {
      if (roomsFilterBlock && roomsFilterBlock.value !== value) roomsFilterBlock.value = value;
      if (roomsFilterBlockMain && roomsFilterBlockMain.value !== value) roomsFilterBlockMain.value = value;
    } else if (type === "room") {
      if (roomsFilterRoom && roomsFilterRoom.value !== value) roomsFilterRoom.value = value;
      if (roomsFilterRoomMain && roomsFilterRoomMain.value !== value) roomsFilterRoomMain.value = value;
    }
  }

  function attachFilterHandlers(selectEl, type) {
    if (!selectEl) return;
    selectEl.addEventListener("change", () => {
      const value = selectEl.value;
      roomsFilterState[type] = value;
      syncFilterSelects(type, value);
      renderRooms();
    });
  }

  // Populate room dropdowns once
  function populateRoomSelect(selectEl) {
    if (!selectEl) return;
    selectEl.innerHTML = '<option value="all">All</option>';
    ROOMS.forEach((room) => {
      const opt = document.createElement("option");
      opt.value = room.number;
      opt.textContent = room.number;
      selectEl.appendChild(opt);
    });
  }

  populateRoomSelect(roomsFilterRoom);
  populateRoomSelect(roomsFilterRoomMain);

  attachFilterHandlers(roomsFilterFloor, "floor");
  attachFilterHandlers(roomsFilterBlock, "block");
  attachFilterHandlers(roomsFilterRoom, "room");

  attachFilterHandlers(roomsFilterFloorMain, "floor");
  attachFilterHandlers(roomsFilterBlockMain, "block");
  attachFilterHandlers(roomsFilterRoomMain, "room");

  if (signInForm) {
    signInForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const name = nameInput.value.trim();
      const role = roleSelect.value;
      const password = passwordInput ? passwordInput.value : "";

      if (!name || !password) {
        alert("Please enter both name and password.");
        return;
      }

      // Very simple demo authentication: shared passwords per role.
      if (role === "admin" && password !== "admin123") {
        alert("Incorrect admin password.");
        return;
      }
      if (role === "student" && password !== "student123") {
        alert("Incorrect student password.");
        return;
      }

      const user = { name, role };
      saveUser(user);
      applyUserState(user);

      // After signing in on the dedicated page, go to main page
      if (!topSignInLink) {
        window.location.href = "hostel.php";
      }
    });
  }

  if (signOutBtn) {
    signOutBtn.addEventListener("click", () => {
      clearUser();
      applyUserState(null);
    });
  }

  if (announcementsList) {
    // Deleting / editing announcements (admin only buttons are shown via CSS)
    announcementsList.addEventListener("click", (e) => {
      const target = e.target;

      if (target.classList.contains("delete-announcement-btn")) {
        const li = target.closest("li");
        if (li) {
          announcementsList.removeChild(li);
        }
        return;
      }

      if (target.classList.contains("edit-announcement-btn")) {
        const li = target.closest("li");
        if (!li || !addAnnouncementForm || !announcementModal) return;

        const titleInput = document.getElementById("new-title");
        const metaInput = document.getElementById("new-meta");
        const bodyInput = document.getElementById("new-body");
        const attachmentInput = document.getElementById("new-attachment");

        if (!titleInput || !metaInput || !bodyInput) return;

        const titleEl = li.querySelector(".announcement-title");
        const metaEl = li.querySelector(".announcement-meta");
        const bodyEl = li.querySelector(".announcement-body");

        titleInput.value = titleEl ? titleEl.textContent : "";
        metaInput.value = metaEl ? metaEl.textContent : "";
        bodyInput.value = bodyEl ? bodyEl.textContent : "";
        if (attachmentInput) {
          // Cannot prefill file input for security reasons; just clear it.
          attachmentInput.value = "";
        }

        editingAnnouncement = li;
        announcementModal.hidden = false;
      }
    });
  }

  
  // Admin modal open/close
  if (openAnnouncementModalBtn && announcementModal) {
    openAnnouncementModalBtn.addEventListener("click", () => {
      editingAnnouncement = null;
      if (addAnnouncementForm) {
        addAnnouncementForm.reset();
      }
      announcementModal.hidden = false;
    });
  }

  if (closeAnnouncementModalBtn && announcementModal) {
    closeAnnouncementModalBtn.addEventListener("click", () => {
      editingAnnouncement = null;
      if (addAnnouncementForm) {
        addAnnouncementForm.reset();
      }
      announcementModal.hidden = true;
    });
  }

  if (announcementModal) {
    announcementModal.addEventListener("click", (e) => {
      if (e.target === announcementModal) {
        editingAnnouncement = null;
        if (addAnnouncementForm) {
          addAnnouncementForm.reset();
        }
        announcementModal.hidden = true;
      }
    });
  }
});

