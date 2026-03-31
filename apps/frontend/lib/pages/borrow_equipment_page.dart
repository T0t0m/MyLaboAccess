import 'package:flutter/material.dart';
import '../models/equipment.dart';



class BorrowEquipmentPage extends StatelessWidget {
  final List<Equipment> equipments;

  const BorrowEquipmentPage({super.key, required this.equipments});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Emprunter du matériel'),
      ),
      body: const Center(
        child: Text('Page pour emprunter du matériel.'),
      ),
    );
  }
}