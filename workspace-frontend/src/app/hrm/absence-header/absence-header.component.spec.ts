import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AbsenceHeaderComponent } from './absence-header.component';

describe('AbsenceHeaderComponent', () => {
  let component: AbsenceHeaderComponent;
  let fixture: ComponentFixture<AbsenceHeaderComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AbsenceHeaderComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AbsenceHeaderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
