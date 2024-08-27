document.addEventListener('DOMContentLoaded', function() {
    fetchRankings();

    function fetchRankings() {
        fetch('get_rankings.php')
            .then(response => response.json())
            .then(data => {
                const rankingsList = document.getElementById('rankings-list');
                rankingsList.innerHTML = '';
                data.forEach(ranking => {
                    const listItem = document.createElement('li');
                    listItem.textContent = `${ranking.name}: ${ranking.score}`;
                    rankingsList.appendChild(listItem);
                });
            });
    }
});
