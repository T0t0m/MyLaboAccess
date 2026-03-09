import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

import '../../services/auth_service.dart';

class AdminScreen extends StatelessWidget {
  const AdminScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Admin Panel'),
      ),
      body: Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Text('Admin-only area (protected).'),
            const SizedBox(height: 18),

            ElevatedButton(
              onPressed: () async {
                final headers = await AuthService().authHeaders();
                final apiUrl = AuthService.apiUrl;

                // Demo call to the admin/dashboard endpoint
                final res = await http.get(Uri.parse('$apiUrl/admin/dashboard'), headers: headers);

                final msg = res.statusCode == 200
                    ? (res.body.isEmpty ? 'OK (empty response)' : jsonEncode(jsonDecode(res.body)))
                    : 'Error ${res.statusCode}: ${res.body}';

                if (!context.mounted) return;
                ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(msg)));
              },
              child: const Text('Fetch admin dashboard'),
            ),
          ],
        ),
      ),
    );
  }
}
