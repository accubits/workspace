import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ExpiredFilterComponent } from './expired-filter.component';

describe('ExpiredFilterComponent', () => {
  let component: ExpiredFilterComponent;
  let fixture: ComponentFixture<ExpiredFilterComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ExpiredFilterComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ExpiredFilterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
