export default async function handler(req, res) {
    // السماح بمرور البيانات لمنع الـ CORS تماماً
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'GET');
    
    const { page } = req.query;
    const API_TOKEN = '99a7877a26a87b3d358e1eff089501c5';
    const cacheBuster = new Date().getTime();
    
    const targetUrl = `https://api.travelpayouts.com/v2/prices/latest?currency=usd&period_type=year&page=${page || 1}&limit=4&token=${API_TOKEN}&_=${cacheBuster}`;

    try {
        const response = await fetch(targetUrl, {
            headers: {
                'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            }
        });
        
        const data = await response.json();
        return res.status(200).json(data);
    } catch (error) {
        return res.status(500).json({ success: false, error: error.message });
    }
}