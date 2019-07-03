import { TestBed, inject } from '@angular/core/testing';

import { FormsApiService } from './forms-api.service';

describe('FormsApiService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [FormsApiService]
    });
  });

  it('should be created', inject([FormsApiService], (service: FormsApiService) => {
    expect(service).toBeTruthy();
  }));
});
