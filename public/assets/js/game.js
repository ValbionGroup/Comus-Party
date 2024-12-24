function quitGameAndBackHome(gameCode) {
    fetch(`/game/${gameCode}/quit`, {
        method: 'DELETE',
    }).then((response) => {
        if (response.ok) {
            window.location.href = '/';
        }
        throw new Error('Failed to quit game');
    });
}