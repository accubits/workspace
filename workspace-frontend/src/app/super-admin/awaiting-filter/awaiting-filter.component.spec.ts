import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AwaitingFilterComponent } from './awaiting-filter.component';

describe('AwaitingFilterComponent', () => {
  let component: AwaitingFilterComponent;
  let fixture: ComponentFixture<AwaitingFilterComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AwaitingFilterComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AwaitingFilterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
