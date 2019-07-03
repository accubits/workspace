import { TestBed, inject } from '@angular/core/testing';

import { TaskApiService } from './task-api.service';

describe('TaskApiService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [TaskApiService]
    });
  });

  it('should be created', inject([TaskApiService], (service: TaskApiService) => {
    expect(service).toBeTruthy();
  }));
});
