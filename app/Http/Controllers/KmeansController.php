<?php

namespace App\Http\Controllers;

use App\Models\Tanaman;
use Illuminate\Http\Request;

class KmeansController extends Controller
{

    public function pemetaan(){
        // Fetch data from the Tanaman model
        $data = Tanaman::with('wilayah')->get()->map(function ($item) {
            return [
                'wilayah' => $item->wilayah,
                'luas_lahan' => (float) $item->luas_lahan,
                'produksi' => (float) $item->produksi,
                'produktivitas' => (float) $item->produktivitas,
                'jenis_hortikultura' => $item->jenis_horikultura,
                'persentase' => $item->persentase,
            ];
        })->toArray();

        // Get unique horticultural types and assign colors
        $horticulturalTypes = collect($data)
            ->pluck('jenis_hortikultura')
            ->unique()
            ->values()
            ->map(function ($type, $index) {
                // Generate a unique color for each type
                $colors = ['#FF9933', '#33CC33', '#3366CC', '#CC33CC', '#FFCC00', '#FF3366', '#00CCCC'];
                return [
                    'name' => $type,
                    'color' => $colors[$index % count($colors)]
                ];
            })
            ->toArray();

        // Run K-means
        srand(42); // Set a specific seed value
        $k = 3; // Number of clusters
        $result = $this->kmeans($data, $k);

        $data['tanaman'] = Tanaman::all();
        $data['clusters'] = $result['clusters'];
        $data['iterations'] = $result['iterations'];
        $data['horticulturalTypes'] = $horticulturalTypes;
        return view('pages.kmeans.pemetaan', $data);
    }

    public function index()
    {
        // Fetch data from the Tanaman model
        $data = Tanaman::with('wilayah')->get()->map(function ($item) {
            return [
                'wilayah' => $item->wilayah,
                'luas_lahan' => (float) $item->luas_lahan,
                'produksi' => (float) $item->produksi,
                'produktivitas' => (float) $item->produktivitas,
                'jenis_hortikultura' => $item->jenis_horikultura,
                'persentase' => $item->persentase,
            ];
        })->toArray();

        // Run K-means
        srand(42); // Set a specific seed value
        $k = 3; // Number of clusters
        $result = $this->kmeans($data, $k);

        $data['tanaman'] = Tanaman::all();
        $data['clusters'] = $result['clusters'];
        $data['iterations'] = $result['iterations'];
        return view('pages.kmeans.index', $data);
    }

    public function initializeCentroids($data, $k)
    {
        $centroids = [];
        $indices = array_rand($data, $k);
        foreach ($indices as $index) {
            $centroids[] = $data[$index];
        }
        return $centroids;
    }

    public function calculateDistance($point1, $point2)
    {
        return sqrt(
            pow($point1['luas_lahan'] - $point2['luas_lahan'], 2) +
            pow($point1['produksi'] - $point2['produksi'], 2) +
            pow($point1['produktivitas'] - $point2['produktivitas'], 2)
        );
    }

    public function assignClusters($data, $centroids)
    {
        $clusters = [];
        foreach ($data as $point) {
            $distances = [];
            foreach ($centroids as $centroid) {
                $distances[] = $this->calculateDistance($point, $centroid);
            }
            $clusterIndex = array_search(min($distances), $distances);
            $clusters[$clusterIndex][] = $point;
        }
        return $clusters;
    }

    public function calculateNewCentroids($clusters)
    {
        $centroids = [];
        foreach ($clusters as $cluster) {
            $luasSum = 0;
            $produksiSum = 0;
            $produktifitasSum = 0;
            $count = count($cluster);
            foreach ($cluster as $point) {
                $luasSum += $point['luas_lahan'];
                $produksiSum += $point['produksi'];
                $produktifitasSum += $point['produktivitas'];
            }
            $centroids[] = [
                'luas_lahan' => $luasSum / $count,
                'produksi' => $produksiSum / $count,
                'produktivitas' => $produktifitasSum / $count
            ];
        }
        return $centroids;
    }

    public function kmeans($data, $k, $iterations = 100)
    {
        $centroids = $this->initializeCentroids($data, $k);
        $iterationData = [];
        $currentCentroids = $centroids;

        for ($i = 0; $i < $iterations; $i++) {
            $clusters = $this->assignClusters($data, $currentCentroids);
            $newCentroids = $this->calculateNewCentroids($clusters);

            // Store iteration data
            $iterationData[] = [
                'iteration' => $i + 1,
                'centroids' => $currentCentroids,
                'new_centroids' => $newCentroids,
                'clusters' => $clusters
            ];

            if ($currentCentroids == $newCentroids) {
                break; // Convergence reached
            }
            $currentCentroids = $newCentroids;
        }

        return [
            'clusters' => $clusters,
            'iterations' => $iterationData
        ];
    }
}
