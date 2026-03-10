class ReturnEquipmentPage extends StatelessWidget {
  final List<Equipment> equipments;

  const ReturnEquipmentPage({super.key, required this.equipments});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Rendre du matériel'),
      ),
      body: const Center(
        child: Text('Page pour rendre du matériel.'),
      ),
    );
  }
}