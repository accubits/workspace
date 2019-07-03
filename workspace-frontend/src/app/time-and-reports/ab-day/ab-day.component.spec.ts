import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AbDayComponent } from './ab-day.component';

describe('AbDayComponent', () => {
  let component: AbDayComponent;
  let fixture: ComponentFixture<AbDayComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AbDayComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AbDayComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
