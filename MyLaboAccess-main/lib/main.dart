import 'package:flutter/material.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'Flutter Demo',
      theme: ThemeData(
        brightness: Brightness.light,
        primaryColor: Colors.white,
        textTheme: const TextTheme(
          bodyLarge: TextStyle(fontFamily: 'Roboto', fontSize: 18, color: Colors.black87),
          bodyMedium: TextStyle(fontFamily: 'Roboto', fontSize: 16, color: Colors.black54),
        ),
        elevatedButtonTheme: ElevatedButtonThemeData(
          style: ElevatedButton.styleFrom(
            backgroundColor: Colors.redAccent.shade700,
            foregroundColor: Colors.white,
            textStyle: const TextStyle(
              fontFamily: 'Roboto',
              fontSize: 16,
              fontWeight: FontWeight.bold,
            ),
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(12),
            ),
            padding: const EdgeInsets.symmetric(vertical: 14, horizontal: 20),
          ),
        ),
      ),
      home: const MyHomePage(title: 'MyLaboAccess'),
    );
  }
}

class MyHomePage extends StatefulWidget {
  const MyHomePage({super.key, required this.title});

  final String title;

  @override
  State<MyHomePage> createState() => _MyHomePageState();
}

class _MyHomePageState extends State<MyHomePage> {
  final List<Equipment> equipments = [
    Equipment('Écrans', 15),
    Equipment('Routeurs', 8),
    Equipment('Switches', 12),
    Equipment('Serveurs', 4),
    Equipment('Câbles réseau', 150),
    Equipment('Points d\'accès WiFi', 6),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(widget.title, style: const TextStyle(fontFamily: 'Roboto', fontSize: 22)),
        backgroundColor: Theme.of(context).primaryColor,
        elevation: 2,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                ElevatedButton(
                  child: const Text('Comment Emprunter ?'),
                  onPressed: () {
                    showDialog(
                      context: context,
                      builder: (context) => AlertDialog(
                        title: const Text('Emprunter du Matériel'),
                        content: const Text(
                            'Vérifiez le nombre de matériels disponibles avant d\'en prendre, puis allez dans l\'onglet "Emprunter du matériel et scannez le QRcode de l\'objet à emprunter".'),
                      ),
                    );
                  },
                ),
                ElevatedButton(
                  child: const Text('Comment Rendre ?'),
                  onPressed: () {
                    showDialog(
                      context: context,
                      builder: (context) => AlertDialog(
                        title: const Text('Rendre du Matériel'),
                        content: const Text(
                            'Sélectionnez le matériel que vous souhaitez rendre dans l\'onglet "Rendre du matériel".'),
                      ),
                    );
                  },
                ),
              ],
            ),
            const SizedBox(height: 16),
            const Text(
              'Liste des équipements:',
              style: TextStyle(
                fontSize: 20,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 16),
            Expanded(
              child: ListView.builder(
                itemCount: equipments.length,
                itemBuilder: (context, index) {
                  return Card(
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                    elevation: 3,
                    child: ListTile(
                      leading: Icon(
                        _getIconForEquipment(equipments[index].name),
                        color: Colors.redAccent.shade700,
                      ),
                      title: Text(
                        equipments[index].name,
                        style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w500),
                      ),
                      trailing: Container(
                        padding: const EdgeInsets.all(8),
                        decoration: BoxDecoration(
                          color: Colors.redAccent.shade700,
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: Text(
                          '${equipments[index].quantity}',
                          style: const TextStyle(
                            color: Colors.white,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                    ),
                  );
                },
              ),
            ),
          ],
        ),
      ),
      floatingActionButton: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          FloatingActionButton.extended(
            heroTag: "borrow",
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => BorrowEquipmentPage(equipments: equipments),
                ),
              );
            },
            label: const Text('Emprunter du matériel'),
            icon: const Icon(Icons.add_shopping_cart),
          ),
          const SizedBox(height: 12),
          FloatingActionButton.extended(
            heroTag: "return",
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => ReturnEquipmentPage(equipments: equipments),
                ),
              );
            },
            label: const Text('Rendre du matériel'),
            icon: const Icon(Icons.assignment_return),
          ),
        ],
      ),
    );
  }

  IconData _getIconForEquipment(String equipment) {
    switch (equipment.toLowerCase()) {
      case 'écrans':
        return Icons.monitor;
      case 'routeurs':
        return Icons.router;
      case 'switches':
        return Icons.device_hub;
      case 'serveurs':
        return Icons.storage;
      case 'câbles réseau':
        return Icons.cable;
      case 'points d\'accès wifi':
        return Icons.wifi;
      default:
        return Icons.devices_other;
    }
  }
}

class BorrowEquipmentPage extends StatelessWidget {
  final List<Equipment> equipments;

  const BorrowEquipmentPage({super.key, required this.equipments});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Emprunter du matériel'),
      ),
      body: Center(
        child: Text('Page pour emprunter du matériel.'),
      ),
    );
  }
}

class ReturnEquipmentPage extends StatelessWidget {
  final List<Equipment> equipments;

  const ReturnEquipmentPage({super.key, required this.equipments});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Rendre du matériel'),
      ),
      body: Center(
        child: Text('Page pour rendre du matériel.'),
      ),
    );
  }
}

class Equipment {
  final String name;
  final int quantity;

  Equipment(this.name, this.quantity);
}
