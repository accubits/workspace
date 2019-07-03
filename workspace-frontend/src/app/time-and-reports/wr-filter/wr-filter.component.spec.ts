import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WrFilterComponent } from './wr-filter.component';

describe('WrFilterComponent', () => {
  let component: WrFilterComponent;
  let fixture: ComponentFixture<WrFilterComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WrFilterComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WrFilterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
