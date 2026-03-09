import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class AuthService {
  static const String apiUrl = "http://localhost/mylaboapi/public/api"; // change to your Laragon API

  Future<bool> login(String email, String password) async {
    final response = await http.post(
      Uri.parse("$apiUrl/login"),
      body: {
        "email": email,
        "password": password,
      },
    );

    if (response.statusCode == 200) {
      final body = jsonDecode(response.body);
      String token = body["token"];

      // If the API returns the user object, try to extract the role.
      // The server may not be ready yet; handle nulls gracefully.
      String? role;
      if (body["user"] != null && body["user"]["role"] != null) {
        role = body["user"]["role"].toString();
      }

      final prefs = await SharedPreferences.getInstance();
      await prefs.setString("token", token);
      if (role != null) await prefs.setString("role", role);

      return true;
    }

    return false;
  }

  Future<void> logout() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove("token");
    await prefs.remove("role");
  }

  Future<bool> isLoggedIn() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString("token") != null;
  }

  Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString("token");
  }

  /// Return the user role previously saved by login (nullable).
  Future<String?> getRole() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString("role");
  }

  /// Convenience helper to check if the stored role is "admin".
  Future<bool> isAdmin() async {
    final role = await getRole();
    return role == 'admin';
  }

  /// Returns headers to add to secured API calls. If token is absent, returns a map without Authorization.
  Future<Map<String, String>> authHeaders() async {
    final token = await getToken();
    final headers = {
      "Accept": "application/json",
    };
    if (token != null) headers["Authorization"] = "Bearer $token";
    return headers;
  }
}
