
export default function useMonths() {
    const months = Array.from({length: 12}, (_, index) => ({
        id: index + 1,
        name: new Date(0, index).toLocaleString('default', { month: 'long' })
    }));

    return {
        months
    };
}
