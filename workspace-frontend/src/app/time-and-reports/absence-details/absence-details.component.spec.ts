import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AbsenceDetailsComponent } from './absence-details.component';

describe('AbsenceDetailsComponent', () => {
  let component: AbsenceDetailsComponent;
  let fixture: ComponentFixture<AbsenceDetailsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AbsenceDetailsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AbsenceDetailsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
