import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DrConfirmedComponent } from './dr-confirmed.component';

describe('DrConfirmedComponent', () => {
  let component: DrConfirmedComponent;
  let fixture: ComponentFixture<DrConfirmedComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DrConfirmedComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DrConfirmedComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
