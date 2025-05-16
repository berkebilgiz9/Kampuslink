function loadClubs(facultyName, page = 1) {
    const clubsContainer = document.getElementById("clubs-container");
    const paginationContainer = document.getElementById("pagination-container");
    const selectedFaculty = document.getElementById("selected-faculty");

    clubsContainer.innerHTML = "Yükleniyor...";
    paginationContainer.innerHTML = ""; 

    
    selectedFaculty.textContent = facultyName;

    
    document.querySelectorAll(".faculty-card").forEach(card => {
        card.classList.remove("active");
    });

   
    const targetCard = Array.from(document.querySelectorAll(".faculty-card")).find(card =>
        card.getAttribute("data-faculty") === facultyName
    );
    if (targetCard) {
        targetCard.classList.add("active");
    }

    fetch(`../ajax/get_clubs_by_faculty.php?faculty=${encodeURIComponent(facultyName)}&page=${page}`)
    .then(response => response.json())
    .then(data => {
        clubsContainer.innerHTML = "";

        if (data.clubs.length === 0) {
            clubsContainer.innerHTML = "<p>Bu fakülteye ait topluluk bulunamadı.</p>";
            return;
        }

        
        const row = document.createElement("div");
        row.classList.add("clubs-row"); 

        data.clubs.forEach(club => {
            
            const clubCard = document.createElement("div");
            clubCard.classList.add("club-card");
            clubCard.innerHTML = `
                <strong>${club.club_name}</strong><br>
                <em>${club.faculty_name}</em><br>
            `;

            
            clubCard.addEventListener("click", () => {
                window.location.href = `club_events.php?club_id=${club.id}`;
            });

            
            const joinButton = document.createElement("button");
            joinButton.textContent = club.user_joined ? "Katıldınız" : "Katıl";
            joinButton.disabled = club.user_joined;
            joinButton.classList.add("join-button");

            joinButton.addEventListener("click", () => {
                const modal = document.getElementById("warningModal");

                fetch('../ajax/check_login_.php') 
                .then(response => response.json())  
                .then(data => {
                   
                    if (!data.isLoggedIn) {
                        modal.style.display = "block";
                        return;
                    }

                   
                    if (!club.user_joined) {
                        joinClub(club.id);
                        joinButton.textContent = "Katıldınız";
                        joinButton.disabled = true;
                    }
                })
                .catch(error => {
                    console.error("Hata oluştu:", error);
                });
            });

            
            const clubContainer = document.createElement("div");
            clubContainer.classList.add("club-container");

            
            clubContainer.appendChild(clubCard);
            clubContainer.appendChild(joinButton);

           
            row.appendChild(clubContainer);
        });

       
        clubsContainer.appendChild(row);

        //Pagination
        if (data.totalPages > 1) {
            paginationContainer.innerHTML = '';
            for (let i = 1; i <= data.totalPages; i++) {
                const pageButton = document.createElement("button");
                pageButton.textContent = i;
                if (i === page) {
                    pageButton.classList.add("active");
                }
                pageButton.addEventListener("click", () => {
                    loadClubs(facultyName, i);
                });
                paginationContainer.appendChild(pageButton);
            }
        }
    })
    .catch(error => {
        console.error("Hata oluştu:", error);
        clubsContainer.innerHTML = `<p>Hata oluştu: ${error.message}</p>`;
    });
}

// Modal işlemleri
const closeBtn = document.querySelector(".close-btn");
const modal = document.getElementById("warningModal");

closeBtn.addEventListener("click", () => {
    modal.style.display = "none";
});

window.addEventListener("click", (event) => {
    if (event.target === modal) {
        modal.style.display = "none";
    }
});

function joinClub(clubId) {
    console.log('Katılma isteği gönderildi: ' + clubId);

    fetch('../ajax/join_club.php', {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ club_id: clubId }) 
    })
    .then(response => response.json())  
    .then(data => {
        if (data.success) {
            alert(data.message); 
        } else {
            alert(data.message); 
        }
    })
    
}
